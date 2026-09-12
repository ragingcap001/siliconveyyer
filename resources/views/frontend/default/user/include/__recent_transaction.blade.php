@php
    $txnIcons = [
        'send_money'    => 'M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3',
        'receive_money' => 'M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18',
        'deposit'       => 'M12 4.5v15m7.5-7.5h-15',
        'withdraw'      => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z',
    ];
@endphp

<div class="overflow-hidden rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] shadow-soft">

    <div class="flex items-center justify-between border-b border-[rgb(var(--line)/0.07)] px-6 py-5">
        <h3 class="text-base font-semibold text-[rgb(var(--text-strong))]">{{ __('Recent Transactions') }}</h3>
        <a href="{{ route('user.transactions') }}" class="link-arrow">{{ __('View all') }}</a>
    </div>

    @if($recentTransactions->isEmpty())
        <p class="px-6 py-14 text-center text-sm text-[rgb(var(--text-muted))]">{{ __('No Data Found') }}</p>
    @else
        {{-- desktop --}}
        <div class="hidden overflow-x-auto lg:block">
            <table class="table-modern">
                <thead>
                <tr>
                    <th>{{ __('Description') }}</th>
                    <th>{{ __('Transactions ID') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Fee') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Gateway') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($recentTransactions as $transaction)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[rgb(var(--line)/0.06)] text-[rgb(var(--text-muted))]">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $txnIcons[$transaction->type->value] ?? 'M16.5 6a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z' }}"/>
                                    </svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-[rgb(var(--text-strong))]">{{ $transaction->description }}</p>
                                    <p class="text-xs text-[rgb(var(--text-muted))]">{{ $transaction->created_at }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="font-mono text-xs text-[rgb(var(--text-muted))]">{{ $transaction->tnx }}</td>

                        <td><span class="badge-brand">{{ ucfirst(str_replace('_',' ',$transaction->type->value)) }}</span></td>

                        <td class="whitespace-nowrap font-display font-bold {{ str_contains(txn_type($transaction->type->value,['+','-']), '-') ? 'text-rose-600 dark:text-rose-400' : 'text-earn-600 dark:text-earn-400' }}">
                            {{ txn_type($transaction->type->value,['+','-']) }}{{ $transaction->amount }} {{ $currency }}
                        </td>

                        <td class="whitespace-nowrap text-sm text-[rgb(var(--text-body))]">{{ $transaction->charge }} {{ $currency }}</td>

                        <td>
                            @if($transaction->status->value === \App\Enums\TxnStatus::Pending->value)
                                <span class="badge-warn">{{ __('Pending') }}</span>
                            @elseif($transaction->status->value === \App\Enums\TxnStatus::Success->value)
                                <span class="badge-earn">{{ __('Success') }}</span>
                            @elseif($transaction->status->value === \App\Enums\TxnStatus::Failed->value)
                                <span class="badge-danger">{{ __('canceled') }}</span>
                            @endif
                        </td>

                        <td class="text-sm text-[rgb(var(--text-body))]">{{ $transaction->method }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- mobile --}}
        <div class="divide-y divide-[rgb(var(--line)/0.06)] lg:hidden">
            @foreach($recentTransactions as $transaction)
                <div class="flex items-center gap-3.5 p-4">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[rgb(var(--line)/0.06)] text-[rgb(var(--text-muted))]">
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $txnIcons[$transaction->type->value] ?? 'M16.5 6a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z' }}"/>
                        </svg>
                    </span>

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-[rgb(var(--text-strong))]">{{ $transaction->description }}</p>
                        <p class="text-xs text-[rgb(var(--text-muted))]">{{ $transaction->created_at }}</p>
                    </div>

                    <div class="shrink-0 text-right">
                        <p class="font-display text-sm font-bold {{ str_contains(txn_type($transaction->type->value,['+','-']), '-') ? 'text-rose-600 dark:text-rose-400' : 'text-earn-600 dark:text-earn-400' }}">
                            {{ txn_type($transaction->type->value,['+','-']) }}{{ $transaction->amount }}
                        </p>
                        <p class="mt-0.5 text-[0.7rem] text-[rgb(var(--text-muted))]">{{ $transaction->method }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
