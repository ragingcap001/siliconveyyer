@php
    /* @var \App\Models\Task|null $task */
    $task = $task ?? null;
@endphp

<form action="{{ $action }}" method="post">
    @csrf

    <div class="row g-3">
        <div class="col-md-8">
            <label class="form-label">{{ __('Task Name') }} <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" required
                   value="{{ old('title', $task->title ?? '') }}" placeholder="{{ __('e.g. Follow our X account') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">{{ __('Category') }}</label>
            <input type="text" name="category" class="form-control"
                   value="{{ old('category', $task->category ?? '') }}" placeholder="{{ __('e.g. Social') }}">
        </div>

        <div class="col-12">
            <label class="form-label">{{ __('Description') }} <span class="text-danger">*</span></label>
            <textarea name="description" class="summernote"
                      required>{{ old('description', $task->description ?? '') }}</textarea>
            <small class="text-muted">{{ __('Shown to workers in full on the task page.') }}</small>
        </div>

        <div class="col-12">
            <label class="form-label">{{ __('Submission Instructions') }}</label>
            <textarea name="instructions" class="form-control" rows="3"
                      placeholder="{{ __('Tell the worker exactly what to do and how to prove it.') }}">{{ old('instructions', $task->instructions ?? '') }}</textarea>
        </div>

        <div class="col-md-4">
            <label class="form-label">{{ __('Pay Amount') }} ({{ $currency }}) <span class="text-danger">*</span></label>
            <input type="text" name="pay_amount" class="form-control" required
                   value="{{ old('pay_amount', $task->pay_amount ?? '') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">{{ __('Proof Type') }}</label>
            <select name="proof_type" class="form-control">
                @foreach($proofTypes as $proofType)
                    <option value="{{ $proofType->value }}"
                        @selected(old('proof_type', $task?->proof_type?->value) === $proofType->value)>{{ $proofType->label() }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">{{ __('Deadline') }}</label>
            <input type="date" name="expires_at" class="form-control"
                   value="{{ old('expires_at', $task?->expires_at?->format('Y-m-d')) }}">
            <small class="text-muted">{{ __('Leave blank for no deadline.') }}</small>
        </div>

        <div class="col-md-4">
            <label class="form-label">{{ __('Total Slots') }}</label>
            <input type="number" name="total_slots" class="form-control" min="0"
                   value="{{ old('total_slots', $task->total_slots ?? 0) }}">
            <small class="text-muted">{{ __('0 = unlimited workers.') }}</small>
        </div>

        <div class="col-md-4">
            <label class="form-label">{{ __('Attempts Per User') }}</label>
            <input type="number" name="per_user_limit" class="form-control" min="1"
                   value="{{ old('per_user_limit', $task->per_user_limit ?? 1) }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">{{ __('Status') }}</label>
            <select name="status" class="form-control">
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}"
                        @selected(old('status', $task?->status?->value ?? 'draft') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12">
            <hr>
            <h6 class="mb-2">{{ __('Recommended Task Takers') }}</h6>
        </div>

        <div class="col-md-4">
            <label class="form-label">{{ __('Minimum Level') }}</label>
            <select name="min_level" class="form-control">
                <option value="1" @selected(old('min_level', $task->min_level ?? 1) == 1)>{{ __('Any level') }}</option>
                @foreach($levels as $level)
                    <option value="{{ $level->level }}"
                        @selected(old('min_level', $task->min_level ?? 1) == $level->level)>{{ $level->level }}
                        - {{ $level->ranking_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">{{ __('Minimum Balance (coins)') }}</label>
            <input type="text" name="min_balance" class="form-control"
                   value="{{ old('min_balance', $task->min_balance ?? 0) }}">
            <small class="text-muted">{{ __('0 = no minimum.') }}</small>
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <div class="form-check">
                <input type="checkbox" name="require_kyc" value="1" class="form-check-input" id="require_kyc_{{ $task->id ?? 'new' }}"
                    @checked(old('require_kyc', $task->require_kyc ?? false))>
                <label class="form-check-label"
                       for="require_kyc_{{ $task->id ?? 'new' }}">{{ __('Require KYC verification') }}</label>
            </div>
        </div>

        <div class="col-12">
            <hr>
            <h6 class="mb-2">{{ __('Payout Options') }}</h6>
            <small class="text-muted d-block mb-2">
                {{ __('Leave all unchecked to offer every payout method you have enabled.') }}
            </small>
            @forelse($payoutMethods as $method)
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="payout_method_ids[]" value="{{ $method->id }}"
                           class="form-check-input"
                           id="pm_{{ $task->id ?? 'new' }}_{{ $method->id }}"
                        @checked(in_array($method->id, old('payout_method_ids', $task->payout_method_ids ?? [])))>
                    <label class="form-check-label"
                           for="pm_{{ $task->id ?? 'new' }}_{{ $method->id }}">{{ $method->name }}</label>
                </div>
            @empty
                <p class="text-danger mb-0">
                    {{ __('No payout methods enabled. Enable them under Withdraw Management first.') }}
                </p>
            @endforelse
        </div>

        <div class="col-12">
            <div class="form-check">
                <input type="checkbox" name="proof_required" value="1" class="form-check-input"
                       id="proof_required_{{ $task->id ?? 'new' }}"
                    @checked(old('proof_required', $task->proof_required ?? true))>
                <label class="form-check-label"
                       for="proof_required_{{ $task->id ?? 'new' }}">{{ __('Require proof before payout') }}</label>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="site-btn grad-btn w-100">
                {{ $task ? __('Update Task') : __('Create Task') }}
            </button>
        </div>
    </div>
</form>
