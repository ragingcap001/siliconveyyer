<?php

namespace App\Models;

use App\Enums\TaskSubmissionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => TaskSubmissionStatus::class,
        'pay_amount' => 'double',
        'reviewed_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    // ============================== relationships ==============================

    public function task()
    {
        return $this->belongsTo(Task::class)->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by')->withDefault();
    }

    /**
     * The payout method the worker chose (one of the admin-enabled options).
     */
    public function payoutMethod()
    {
        return $this->belongsTo(WithdrawMethod::class, 'payout_method_id')->withDefault();
    }

    /**
     * The worker's saved payout credentials for that method.
     */
    public function withdrawAccount()
    {
        return $this->belongsTo(WithdrawAccount::class, 'withdraw_account_id')->withDefault();
    }

    // ============================== scopes ==============================

    public function scopePending($query)
    {
        return $query->where('status', TaskSubmissionStatus::Pending);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', TaskSubmissionStatus::Approved);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', TaskSubmissionStatus::Rejected);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ============================== state ==============================

    public function isPending(): bool
    {
        return $this->status === TaskSubmissionStatus::Pending;
    }

    public function isApproved(): bool
    {
        return $this->status === TaskSubmissionStatus::Approved;
    }

    public function isRejected(): bool
    {
        return $this->status === TaskSubmissionStatus::Rejected;
    }

    /**
     * The submitted proof, whichever shape the task asked for.
     */
    public function proofValue(): ?string
    {
        return $this->proof_file ?? $this->proof_link ?? $this->proof_text;
    }
}
