@php
    $isVerified = $user->kyc === \App\Enums\KYCStatus::Verified->value;
    $isPending  = $user->kyc === \App\Enums\KYCStatus::Pending->value;
@endphp

@unless($isVerified)
    <div x-data="{ open: true }" x-show="open" x-cloak
         class="mb-6 flex flex-col gap-4 rounded-2xl border border-amber-500/25 bg-amber-500/[0.07] p-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-start gap-3.5">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-500/15 text-amber-600 dark:text-amber-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                </svg>
            </span>
            <div>
                @if($isPending)
                    <p class="text-sm font-semibold text-[rgb(var(--text-strong))]">{{ __('KYC Pending') }}</p>
                    <p class="mt-0.5 text-sm text-[rgb(var(--text-muted))]">
                        {{ __('Your documents are being reviewed. Tasks marked KYC will unlock once verified.') }}
                    </p>
                @else
                    <p class="text-sm font-semibold text-[rgb(var(--text-strong))]">
                        {{ __('Verify your identity to unlock every task') }}
                    </p>
                    <p class="mt-0.5 text-sm text-[rgb(var(--text-muted))]">
                        {{ __('Some tasks require KYC before you can claim them.') }}
                    </p>
                @endif
            </div>
        </div>

        <div class="flex shrink-0 gap-2">
            @unless($isPending)
                <a href="{{ route('user.kyc') }}" class="btn-primary btn-sm">
                    {{ __('Submit Now') }}
                </a>
            @endunless
            <button @click="open = false" type="button" class="btn-ghost btn-sm">{{ __('Later') }}</button>
        </div>
    </div>
@endunless
