@extends('frontend::layouts.user')

@section('title'){{ __('My Tasks') }}@endsection
@section('subtitle'){{ __('Every task you have claimed, and where each one stands.') }}@endsection

@section('content')

    {{-- filter --}}
    <form action="{{ route('user.task.history') }}" method="get"
          class="mb-6 flex flex-col gap-3 rounded-2xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-4 shadow-soft sm:flex-row sm:items-center">

        <select name="status" class="field sm:w-56">
            <option value="">{{ __('All Statuses') }}</option>
            @foreach(\App\Enums\TaskSubmissionStatus::cases() as $status)
                <option value="{{ $status->value }}" @selected(request('status') === $status->value)>
                    {{ $status->label() }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn-primary shrink-0">{{ __('Filter') }}</button>

        @if(request()->filled('status'))
            <a href="{{ route('user.task.history') }}" class="btn-ghost btn-sm shrink-0">{{ __('Clear') }}</a>
        @endif
    </form>

    <div class="overflow-hidden rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] shadow-soft">

        {{-- desktop table --}}
        <div class="hidden overflow-x-auto lg:block">
            <table class="table-modern">
                <thead>
                <tr>
                    <th>{{ __('Task') }}</th>
                    <th>{{ __('Pay') }}</th>
                    <th>{{ __('Payout To') }}</th>
                    <th>{{ __('Proof') }}</th>
                    <th>{{ __('Submitted') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th class="text-right">{{ __('Action') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($submissions as $submission)
                    <tr>
                        <td>
                            <p class="font-semibold text-[rgb(var(--text-strong))]">{{ $submission->task->title }}</p>
                            @if($submission->attempt > 1)
                                <p class="mt-0.5 text-xs text-[rgb(var(--text-muted))]">
                                    {{ __('Attempt') }} {{ $submission->attempt }}
                                </p>
                            @endif
                        </td>

                        <td>
                            <span class="font-display font-bold text-earn-600 dark:text-earn-400">
                                {{ $currencySymbol }}{{ $submission->pay_amount }}
                            </span>
                        </td>

                        <td>
                            <p class="text-sm text-[rgb(var(--text-body))]">{{ $submission->payoutMethod?->name ?: '—' }}</p>
                            @if($submission->withdrawAccount?->method_name)
                                <p class="mt-0.5 text-xs text-[rgb(var(--text-muted))]">{{ $submission->withdrawAccount->method_name }}</p>
                            @endif
                        </td>

                        <td>
                            @if($submission->proof_file)
                                <a href="{{ asset('assets/'.$submission->proof_file) }}" target="_blank" class="badge-brand">{{ __('File') }}</a>
                            @elseif($submission->proof_link)
                                <a href="{{ $submission->proof_link }}" target="_blank" class="badge-brand">{{ __('Link') }}</a>
                            @elseif($submission->proof_text)
                                <span class="text-sm text-[rgb(var(--text-muted))]">{{ Str::limit($submission->proof_text, 30) }}</span>
                            @else
                                <span class="text-sm text-[rgb(var(--text-muted))]">{{ __('Not submitted') }}</span>
                            @endif
                        </td>

                        <td class="whitespace-nowrap text-sm text-[rgb(var(--text-muted))]">
                            {{ $submission->created_at->format('d M Y h:i') }}
                        </td>

                        <td>
                            <span class="badge {{ $submission->status->color() === 'success' ? 'badge-earn'
                                               : ($submission->status->color() === 'danger' ? 'badge-danger' : 'badge-warn') }}">
                                {{ $submission->status->label() }}
                            </span>
                            @if($submission->isRejected() && $submission->admin_note)
                                <p class="mt-1 max-w-[220px] text-xs leading-snug text-rose-600 dark:text-rose-400">
                                    {{ $submission->admin_note }}
                                </p>
                            @endif
                        </td>

                        <td class="text-right">
                            <a href="{{ route('user.task.show', $submission->task_id) }}" class="btn-outline btn-sm">
                                {{ __('Open') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center">
                            <p class="text-sm text-[rgb(var(--text-muted))]">{{ __('You have not taken any tasks yet.') }}</p>
                            <a href="{{ route('user.task.index') }}" class="btn-primary btn-sm mt-4">{{ __('Browse tasks') }}</a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- mobile cards --}}
        <div class="divide-y divide-[rgb(var(--line)/0.06)] lg:hidden">
            @forelse($submissions as $submission)
                <div class="p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-[rgb(var(--text-strong))]">{{ $submission->task->title }}</p>
                            <p class="mt-1 text-xs text-[rgb(var(--text-muted))]">
                                {{ $submission->created_at->format('d M Y h:i') }}
                                @if($submission->attempt > 1) &middot; {{ __('Attempt') }} {{ $submission->attempt }} @endif
                            </p>
                        </div>
                        <span class="font-display shrink-0 font-bold text-earn-600 dark:text-earn-400">
                            {{ $currencySymbol }}{{ $submission->pay_amount }}
                        </span>
                    </div>

                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <span class="badge {{ $submission->status->color() === 'success' ? 'badge-earn'
                                            : ($submission->status->color() === 'danger' ? 'badge-danger' : 'badge-warn') }}">
                            {{ $submission->status->label() }}
                        </span>
                        <span class="badge-neutral">{{ $submission->payoutMethod?->name ?: '—' }}</span>
                    </div>

                    @if($submission->isRejected() && $submission->admin_note)
                        <p class="mt-2 text-xs leading-snug text-rose-600 dark:text-rose-400">{{ $submission->admin_note }}</p>
                    @endif

                    <a href="{{ route('user.task.show', $submission->task_id) }}" class="btn-outline btn-sm btn-block mt-3">
                        {{ __('Open') }}
                    </a>
                </div>
            @empty
                <div class="p-10 text-center">
                    <p class="text-sm text-[rgb(var(--text-muted))]">{{ __('You have not taken any tasks yet.') }}</p>
                    <a href="{{ route('user.task.index') }}" class="btn-primary btn-sm mt-4">{{ __('Browse tasks') }}</a>
                </div>
            @endforelse
        </div>
    </div>

    @if($submissions->hasPages())
        <div class="mt-6">{{ $submissions->links() }}</div>
    @endif

@endsection
