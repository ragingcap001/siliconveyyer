<?php

namespace App\Models;

use App\Enums\KYCStatus;
use App\Enums\TaskProofType;
use App\Enums\TaskStatus;
use App\Enums\TaskSubmissionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => TaskStatus::class,
        'proof_type' => TaskProofType::class,
        'payout_method_ids' => 'array',
        'pay_amount' => 'double',
        'min_balance' => 'double',
        'require_kyc' => 'boolean',
        'total_slots' => 'integer',
        'filled_slots' => 'integer',
        'per_user_limit' => 'integer',
        'min_level' => 'integer',
        'expires_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'draft',
        'min_level' => 1,
        'per_user_limit' => 1,
        'min_balance' => 0,
        'require_kyc' => false,
        'total_slots' => 0,
        'filled_slots' => 0,
    ];

    // ============================== relationships ==============================

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by')->withDefault();
    }

    // ============================== scopes ==============================

    public function scopeActive($query)
    {
        return $query->where('status', TaskStatus::Active);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', TaskStatus::Active)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->where(function ($q) {
                // open slot available (total_slots = 0 means unlimited)
                $q->where('total_slots', 0)
                    ->orWhereColumn('filled_slots', '<', 'total_slots');
            });
    }

    // ============================== availability ==============================

    public function isOpen(): bool
    {
        if ($this->status !== TaskStatus::Active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return $this->slotsRemaining() !== 0;
    }

    /**
     * Remaining slots. -1 means unlimited.
     */
    public function slotsRemaining(): int
    {
        if (! $this->total_slots) {
            return -1;
        }

        return max(0, $this->total_slots - $this->filled_slots);
    }

    public function hasUnlimitedSlots(): bool
    {
        return $this->slotsRemaining() === -1;
    }

    /**
     * Attempts the user has left. Rejected submissions do not consume an attempt,
     * so a rejected worker may correct and resubmit.
     */
    public function attemptsRemainingFor(User $user): int
    {
        $used = $this->submissions()
            ->where('user_id', $user->id)
            ->where('status', '!=', TaskSubmissionStatus::Rejected)
            ->count();

        return max(0, (int) $this->per_user_limit - $used);
    }

    public function hasPendingSubmission(User $user): bool
    {
        return $this->submissions()
            ->where('user_id', $user->id)
            ->where('status', TaskSubmissionStatus::Pending)
            ->exists();
    }

    // ============================== eligibility ==============================

    /**
     * Returns null when the user may take the task, otherwise the reason they may not.
     * This is the single source of truth shared by the browse page, the claim
     * endpoint and the admin preview.
     */
    public function eligibilityErrorFor(User $user): ?string
    {
        if (! $user->status) {
            return __('Your account is not active.');
        }

        if (! $this->isOpen()) {
            return __('This task is no longer available.');
        }

        if ($this->require_kyc && setting('kyc_verification', 'permission')
            && $user->kyc !== KYCStatus::Verified->value) {
            return __('KYC verification is required for this task.');
        }

        $userLevel = (int) ($user->rank?->level ?? 1);
        if ((int) $this->min_level > 1 && $userLevel < (int) $this->min_level) {
            return __('You need to reach level :level to take this task.', ['level' => $this->min_level]);
        }

        if ((float) $this->min_balance > 0 && (float) $user->balance < (float) $this->min_balance) {
            return __('This task requires a minimum balance of :amount.', [
                'amount' => setting('currency_symbol', 'global') . $this->min_balance,
            ]);
        }

        if ($this->hasPendingSubmission($user)) {
            return __('You already have a submission pending review for this task.');
        }

        if ($this->attemptsRemainingFor($user) < 1) {
            return __('You have used all your attempts for this task.');
        }

        return null;
    }

    /**
     * Tasks this user is allowed to see and claim.
     */
    public function scopeAvailableFor($query, User $user)
    {
        return $query->open()->get()
            ->filter(fn (Task $task) => $task->eligibilityErrorFor($user) === null)
            ->values();
    }

    // ============================== payout ==============================

    /**
     * The payout options offered for this task. Defaults to every payout method the
     * admin has enabled, narrowed to the task's own selection when one exists.
     */
    public function payoutMethods()
    {
        return WithdrawMethod::where('status', true)
            ->when(
                ! empty($this->payout_method_ids),
                fn ($q) => $q->whereIn('id', $this->payout_method_ids)
            )
            ->get();
    }
}
