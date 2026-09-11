@extends('frontend::layouts.user')
@section('title')
    {{ __('Available Tasks') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('Available Tasks') }}</h3>
                </div>
                <div class="site-card-body">
                    <form action="{{ route('user.task.index') }}" method="get" class="row g-2 mb-4">
                        <div class="col-md-4">
                            <input type="text" name="query" class="form-control"
                                   placeholder="{{ __('Search tasks') }}" value="{{ request('query') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="category" class="form-control">
                                <option value="">{{ __('All Categories') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}"
                                        @selected(request('category') === $category)>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="site-btn grad-btn">{{ __('Filter') }}</button>
                        </div>
                    </form>

                    <div class="row g-3">
                        @forelse($tasks as $task)
                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="site-card h-100">
                                    <div class="site-card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="mb-0">{{ $task->title }}</h5>
                                            <span class="site-badge success">
                                                {{ $currencySymbol }}{{ $task->pay_amount }}
                                            </span>
                                        </div>

                                        @if($task->category)
                                            <span class="site-badge info mb-2">{{ $task->category }}</span>
                                        @endif

                                        <p class="text-muted small mb-2">
                                            {{ Str::limit(strip_tags($task->description), 140) }}
                                        </p>

                                        <ul class="list-unstyled small mb-3">
                                            <li>
                                                <i icon-name="bar-chart-2"></i>
                                                {{ __('Level') }} {{ $task->min_level }}+
                                            </li>
                                            @if($task->require_kyc)
                                                <li><i icon-name="shield"></i> {{ __('KYC required') }}</li>
                                            @endif
                                            @if($task->min_balance > 0)
                                                <li>
                                                    <i icon-name="coins"></i>
                                                    {{ __('Min balance') }} {{ $currencySymbol }}{{ $task->min_balance }}
                                                </li>
                                            @endif
                                            <li>
                                                <i icon-name="users"></i>
                                                @if($task->hasUnlimitedSlots())
                                                    {{ __('Unlimited takers') }}
                                                @else
                                                    {{ $task->slotsRemaining() }} {{ __('slots left') }}
                                                @endif
                                            </li>
                                            @if($task->expires_at)
                                                <li>
                                                    <i icon-name="clock"></i>
                                                    {{ __('Ends') }} {{ $task->expires_at->format('d M Y') }}
                                                </li>
                                            @endif
                                        </ul>

                                        @if($task->eligibility_error)
                                            <div class="site-badge warning w-100 mb-2">
                                                {{ $task->eligibility_error }}
                                            </div>
                                            <a href="{{ route('user.task.show', $task->id) }}"
                                               class="site-btn outline-btn w-100">{{ __('View Details') }}</a>
                                        @else
                                            <a href="{{ route('user.task.show', $task->id) }}"
                                               class="site-btn grad-btn w-100">{{ __('View & Take Task') }}</a>
                                        @endif

                                        @if($task->my_submission)
                                            <div class="site-badge {{ $task->my_submission->status->color() }} w-100 mt-2">
                                                {{ __('You submitted this') }}:
                                                {{ $task->my_submission->status->label() }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="site-card">
                                    <div class="site-card-body text-center">
                                        <p class="mb-0 text-muted">
                                            {{ __('No tasks are available to you right now. Check back soon.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-3">
                        {{ $tasks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
