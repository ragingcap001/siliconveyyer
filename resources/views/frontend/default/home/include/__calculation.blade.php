@php
    // Title, image and optional video are all edited by the admin from the
    // Intro Section screen. This replaced the old investment profit calculator.
    $videoUrl = $data['intro_video'] ?? null;
    $embedUrl = null;

    if ($videoUrl) {
        // vimeo.com/ID  or  youtube.com/watch?v=ID  ->  embeddable url
        if (preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $m)) {
            $embedUrl = 'https://player.vimeo.com/video/' . $m[1];
        } elseif (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $videoUrl, $m)) {
            $embedUrl = 'https://www.youtube.com/embed/' . $m[1];
        }
    }
@endphp

<section class="section-style-2 light-blue-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-12">
                <div class="section-title text-center">
                    <h4 data-aos="fade-down" data-aos-duration="2000">{{ $data['calculation_title_small'] ?? '' }}</h4>
                    <h2 data-aos="fade-down" data-aos-duration="1500">{{ $data['calculation_title_big'] ?? '' }}</h2>
                </div>
            </div>
        </div>

        <div class="row align-items-center justify-content-center">
            @if(!empty($data['calculation_left_img']))
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="section-img" data-aos="fade-right" data-aos-duration="2000">
                        <img src="{{ asset($data['calculation_left_img']) }}" alt=""/>
                    </div>
                </div>
            @endif

            @if($embedUrl)
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="video-wrap" data-aos="fade-left" data-aos-duration="2000">
                        <iframe
                            src="{{ $embedUrl }}"
                            frameborder="0"
                            allow="autoplay; fullscreen; picture-in-picture"
                            allowfullscreen
                            style="width: 100%; aspect-ratio: 16 / 9; border: 0;"
                        ></iframe>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
