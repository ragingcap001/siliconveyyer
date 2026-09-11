<div class="row">
    <div class="col-xl-12">
        <div class="site-card">
            <div class="site-card-header">
                <h3 class="title">{{ __('Submissions Awaiting Review') }}</h3>
                <a href="{{ route('admin.task.submission.pending') }}" class="site-badge info">
                    {{ __('View all') }}
                </a>
            </div>
            <div class="site-card-body table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __('Worker') }}</th>
                        <th>{{ __('Task') }}</th>
                        <th>{{ __('Pay') }}</th>
                        <th>{{ __('Proof') }}</th>
                        <th>{{ __('Submitted') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($data['pending_submissions'] as $submission)
                        <tr>
                            <td>
                                <strong>{{ safe($submission->user->username) }}</strong>
                            </td>
                            <td>{{ $submission->task->title }}</td>
                            <td>{{ $currencySymbol }}{{ $submission->pay_amount }}</td>
                            <td>
                                @if($submission->proof_file)
                                    <span class="site-badge info">{{ __('File') }}</span>
                                @elseif($submission->proof_link)
                                    <span class="site-badge info">{{ __('Link') }}</span>
                                @elseif($submission->proof_text)
                                    <span class="site-badge info">{{ __('Text') }}</span>
                                @else
                                    <span class="text-muted">{{ __('Awaiting proof') }}</span>
                                @endif
                            </td>
                            <td>{{ $submission->created_at->format('d M Y h:i') }}</td>
                            <td>
                                <a href="{{ route('admin.task.submission.show', $submission->id) }}"
                                   class="round-icon-btn primary-btn" title="{{ __('Review') }}">
                                    <i icon-name="eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                {{ __('Nothing waiting for review.') }}
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
