@php
    // Headings come from the landing data the admin edits; the cards are the
    // newest tasks that are actually open right now.
    $featuredTasks = \App\Models\Task::open()->latest()->take(4)->get();
@endphp

<section class="white-bg section-style-2">
    @if(!empty($data['left_top_img']))
        <div class="bat-left" style="background: url({{ asset($data['left_top_img']) }}) repeat;"
             data-aos="fade-down-right" data-aos-duration="2000"></div>
    @endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-12">
                <div class="section-title text-center">
                    <h4 data-aos="fade-down" data-aos-duration="2000">{{ $data['title_small'] ?? '' }}</h4>
                    <h2 data-aos="fade-down" data-aos-duration="1500">{{ $data['title_big'] ?? '' }}</h2>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse($featuredTasks as $task)
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="single-investment-plan" data-aos="fade-down" data-aos-duration="2000">
                        <div class="investment-plan-name">
                            <h4 class="name">{{ $task->title }}</h4>
                        </div>
                        <div class="investment-plan-info">
                            <div class="single-info">
                                <p class="title">{{ __('Pay') }}</p>
                                <h4 class="value">{{ $currencySymbol }}{{ $task->pay_amount }}</h4>
                            </div>
                            <div class="single-info">
                                <p class="title">{{ __('Slots Left') }}</p>
                                <h4 class="value">
                                    {{ $task->hasUnlimitedSlots() ? __('Unlimited') : $task->slotsRemaining() }}
                                </h4>
                            </div>
                        </div>
                        <a href="{{ route('user.task.show', $task->id) }}" class="site-btn grad-btn w-100">
                            {{ __('View Task') }}
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-xl-12">
                    <p class="text-center">{{ __('No tasks are available right now.') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
