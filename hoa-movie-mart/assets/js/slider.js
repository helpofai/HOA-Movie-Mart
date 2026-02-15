jQuery(document).ready(function($) {
    // 1. Hero Slider
    if ($('.hero-slider').length) {
        new Swiper('.hero-slider', {
            loop: true,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            speed: 1200,
            autoplay: { 
                delay: 7000, 
                disableOnInteraction: false 
            },
            watchSlidesProgress: true,
            pagination: { 
                el: '.swiper-pagination', 
                clickable: true 
            },
            navigation: { 
                nextEl: '.swiper-button-next', 
                prevEl: '.swiper-button-prev' 
            },
            on: {
                init: function() {
                    // Small delay to ensure animations start correctly
                    $('.swiper-slide-active').addClass('animate-content');
                },
                slideChange: function() {
                    $('.hero-content').removeClass('animate-content');
                    setTimeout(function() {
                        $('.swiper-slide-active .hero-content').addClass('animate-content');
                    }, 50);
                }
            }
        });
    }

    // 2. Row Sliders (Trending & Coming Soon)
    $('.movie-row-slider').each(function() {
        var $this = $(this);
        new Swiper(this, {
            slidesPerView: 2,
            spaceBetween: 20,
            navigation: {
                nextEl: $this.find('.swiper-button-next')[0],
                prevEl: $this.find('.swiper-button-prev')[0],
            },
            breakpoints: {
                640: { slidesPerView: 3 },
                992: { slidesPerView: 5 },
                1200: { slidesPerView: 6 }
            }
        });
    });
});