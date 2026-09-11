<div
    class="tab-pane fade"
    id="pills-transfer"
    role="tabpanel"
    aria-labelledby="pills-transfer-tab"
>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h4 class="title">{{ __('Task Submissions') }}</h4>
                </div>
                <div class="site-card-body table-responsive">
                    @php
                        $submissions = $user->taskSubmissions()->with(['task', 'payoutMethod'])->latest()->take(50)->get();
                    @endphp

                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{ __('Task') }}</th>
                            <th>{{ __('Pay') }}</th>
                            <th>{{ __('Payout Method') }}</th>
                            <th>{{ __('Proof') }}</th>
                            <th>{{ __('Attempt') }}</th>
                            <th>{{ __('Submitted') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($submissions as $submission)
                            <tr>
                                <td>{{ $submission->task->title ?? '—' }}</td>
                                <td>
                                    <strong>{{ $currencySymbol }}{{ $submission->pay_amount }}</strong>
                                </td>
                                <td>{{ $submission->payoutMethod->name ?: '—' }}</td>
                                <td>
                                    @if($submission->proof_file)
                                        <a href="{{ asset('assets/'.$submission->proof_file) }}"
                                           target="_blank" class="site-badge info">{{ __('File') }}</a>
                                    @elseif($submission->proof_link)
                                        <a href="{{ $submission->proof_link }}" target="_blank"
                                           class="site-badge info">{{ __('Link') }}</a>
                                    @elseif($submission->proof_text)
                                        <span class="small">{{ Str::limit($submission->proof_text, 30) }}</span>
                                    @else
                                        <span class="text-muted">{{ __('Awaiting proof') }}</span>
                                    @endif
                                </td>
                                <td>{{ $submission->attempt }}</td>
                                <td>{{ $submission->created_at->format('d M Y h:i') }}</td>
                                <td>
                                    <div class="site-badge {{ $submission->status->color() }}">
                                        {{ $submission->status->label() }}
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.task.submission.show', $submission->id) }}"
                                       class="round-icon-btn primary-btn" title="{{ __('Review') }}">
                                        <i icon-name="eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    {{ __('This user has not taken any tasks yet.') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
