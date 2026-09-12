@extends('frontend::layouts.user')

@section('title'){{ __('Referral') }}@endsection
@section('subtitle'){{ __('Invite people, and earn a share of what they earn on tasks.') }}@endsection

@section('content')
    <div x-data="{ tab: 'generalTarget' }" class="space-y-6">

        {{-- referral link card --}}
        <div data-reveal class="relative overflow-hidden rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-6 shadow-soft sm:p-8"
             style="background-image: linear-gradient(135deg, rgb(var(--brand-500)/0.05), transparent 60%);">
            <div class="absolute w-40 h-40 pointer-events-none dot-field -right-8 -top-8"></div>

            <div class="relative">
                <h3 class="text-base font-semibold text-[rgb(var(--text-strong))]">{{ __('Referral URL') }}</h3>
                <p class="mt-1 text-sm text-[rgb(var(--text-muted))]">
                    {{ __('Share this link. Everyone who signs up through it becomes part of your network.') }}
                </p>

                <div class="flex flex-col gap-2 mt-5 sm:flex-row">
                    <input id="refLink" type="text" readonly value="{{ $getReferral->link }}"
                           class="flex-1 font-mono text-xs field"/>
                    <button type="button" onclick="copyRef()" class="btn-primary shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184"/>
                        </svg>
                        <span id="copy">{{ __('Copy Url') }}</span>
                    </button>
                </div>
                <input id="copied" hidden value="{{ __('Copied') }}">

                <p class="mt-3 text-sm text-[rgb(var(--text-muted))]">
                    <b class="text-[rgb(var(--text-strong))]">{{ $getReferral->relationships()->count() }}</b>
                    {{ __('peoples are joined by using this URL') }}
                </p>
            </div>
        </div>

        {{-- referral tree --}}
        @if(setting('site_referral','global') == 'level' && auth()->user()->referrals->count() > 0)
            <div class="rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-6 shadow-soft">
                <h3 class="mb-6 text-base font-semibold text-[rgb(var(--text-strong))]">{{ __('Your Network') }}</h3>
                <section class="management-hierarchy">
                    <div class="hv-container">
                        <div class="hv-wrapper">
                            @include('frontend::referral.include.__tree', ['levelUser' => auth()->user(), 'level' => $level, 'depth' => 1, 'me' => true])
                        </div>
                    </div>
                </section>
            </div>
        @endif

        {{-- referral logs --}}
        <div class="overflow-hidden rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] shadow-soft">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[rgb(var(--line)/0.07)] p-6">
                <h3 class="text-base font-semibold text-[rgb(var(--text-strong))]">{{ __('All Referral Logs') }}</h3>
                <span class="badge-earn">{{ __('Referral Profit:') }} {{ $totalReferralProfit }} {{ $currency }}</span>
            </div>

            {{-- tabs --}}
            <div class="flex gap-2 overflow-x-auto border-b border-[rgb(var(--line)/0.07)] px-6 pt-5">
                <button type="button" @click="tab = 'generalTarget'"
                        :class="tab === 'generalTarget' ? 'border-brand-500 text-brand-600 dark:text-brand-300' : 'border-transparent text-[rgb(var(--text-muted))] hover:text-[rgb(var(--text-strong))]'"
                        class="flex items-center gap-2 px-4 pb-3 text-sm font-semibold transition-colors border-b-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                    </svg>
                    {{ __('General') }}
                </button>

                @foreach($referrals->keys() as $raw)
                    @php $target = json_decode($raw, true); @endphp
                    <button type="button" @click="tab = 't{{ $target['id'] }}'"
                            :class="tab === 't{{ $target['id'] }}' ? 'border-brand-500 text-brand-600 dark:text-brand-300' : 'border-transparent text-[rgb(var(--text-muted))] hover:text-[rgb(var(--text-strong))]'"
                            class="flex items-center gap-2 px-4 pb-3 text-sm font-semibold transition-colors border-b-2 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25"/>
                        </svg>
                        @if(setting('site_referral','global') == 'level')
                            {{ __('Level') }} {{ $target['the_order'] }}
                        @else
                            {{ $target['name'] }}
                        @endif
                    </button>
                @endforeach
            </div>

            <div class="p-6">
                {{-- general pane --}}
                <div x-show="tab === 'generalTarget'" x-cloak>
                    @if($generalReferrals->isEmpty())
                        <div class="py-12 text-center">
                            <p class="text-sm text-[rgb(var(--text-muted))]">{{ __('No Data Found') }}</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="table-modern">
                                <thead>
                                <tr>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Transactions ID') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($generalReferrals as $raw)
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <span class="flex items-center justify-center h-9 w-9 shrink-0 rounded-xl bg-earn-500/10">
                                                    <svg class="w-4 h-4 text-earn-600 dark:text-earn-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                                                    </svg>
                                                </span>
                                                <div>
                                                    <p class="font-semibold text-[rgb(var(--text-strong))]">{{ $raw->description }}</p>
                                                    <p class="text-xs text-[rgb(var(--text-muted))]">{{ $raw->created_at }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="font-mono text-xs font-semibold">{{ $raw->tnx }}</td>
                                        <td class="font-semibold text-earn-600 dark:text-earn-400">+{{ $raw->amount }} {{ $currency }}</td>
                                        <td><span class="badge-earn">{{ $raw->status }}</span></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-5">{{ $generalReferrals->links() }}</div>
                    @endif
                </div>

                {{-- per-target panes --}}
                @foreach($referrals as $target => $referral)
                    @php $target = json_decode($target, true); @endphp
                    <div x-show="tab === 't{{ $target['id'] }}'" x-cloak>
                        @if($referral->isEmpty())
                            <div class="py-12 text-center">
                                <p class="text-sm text-[rgb(var(--text-muted))]">{{ __('No Data Found') }}</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="table-modern">
                                    <thead>
                                    <tr>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Transactions ID') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($referral->sortDesc() as $raw)
                                        <tr>
                                            <td>
                                                <div class="flex items-center gap-3">
                                                    <span class="flex items-center justify-center h-9 w-9 shrink-0 rounded-xl bg-earn-500/10">
                                                        <svg class="w-4 h-4 text-earn-600 dark:text-earn-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                                                        </svg>
                                                    </span>
                                                    <div>
                                                        <p class="font-semibold text-[rgb(var(--text-strong))]">{{ $raw->description }}</p>
                                                        <p class="text-xs text-[rgb(var(--text-muted))]">{{ $raw->created_at }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="font-mono text-xs font-semibold">{{ $raw->tnx }}</td>
                                            <td><span class="badge-brand">{{ $raw->target_type }}</span></td>
                                            <td class="font-semibold text-earn-600 dark:text-earn-400">+{{ $raw->amount }} {{ $currency }}</td>
                                            <td><span class="badge-earn">{{ $raw->status }}</span></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function copyRef() {
            var copyApi = document.getElementById("refLink");
            copyApi.select();
            copyApi.setSelectionRange(0, 999999999);
            document.execCommand('copy');
            $('#copy').text($('#copied').val())
        }
    </script>
@endsection
