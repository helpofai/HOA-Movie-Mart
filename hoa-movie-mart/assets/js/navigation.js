/**
 * Theme Navigation JS - Premium Mobile Logic
 */
jQuery(document).ready(function($) {
    var $header = $('.site-header');
    var $menuToggle = $('.menu-toggle');
    var $drawer = $('.mobile-menu-drawer');
    var $overlay = $('.drawer-overlay');
    var $drawerClose = $('.drawer-close');
    var $body = $('body');
    
    // Search Overlay Logic
    var $searchTrigger = $('.search-trigger');
    var $searchOverlay = $('.hoa-search-overlay');
    var $searchClose = $('.search-overlay-close');
    var $searchInput = $searchOverlay.find('.search-field');

    // Mobile Drawer Toggle
    function toggleDrawer() {
        $menuToggle.toggleClass('is-active');
        $drawer.toggleClass('is-active');
        $overlay.toggleClass('is-active');
        $body.toggleClass('menu-open');
    }

    $menuToggle.on('click', toggleDrawer);
    $drawerClose.on('click', toggleDrawer);
    $overlay.on('click', toggleDrawer);

    // Search Overlay Toggle
    $searchTrigger.on('click', function() {
        $searchOverlay.addClass('is-active');
        $body.addClass('search-open');
        setTimeout(function() {
            $searchInput.focus();
        }, 300);
    });

    $searchClose.on('click', function() {
        $searchOverlay.removeClass('is-active');
        $body.removeClass('search-open');
    });

    // Close search on ESC
    $(document).on('keyup', function(e) {
        if (e.key === "Escape") {
            $searchOverlay.removeClass('is-active');
            $body.removeClass('search-open');
        }
    });

    // Sub-menus logic for Mobile Drawer
    $(document).on('click', '.mobile-nav-list .menu-item-has-children > a', function(e) {
        e.preventDefault();
        var $parent = $(this).parent();
        $parent.toggleClass('is-open');
        $parent.find('> .sub-menu').slideToggle(300);
    });

    // Back to Top Logic
    var $backToTop = $('#back-to-top');
    var $progressPath = $('.back-to-top .progress-circle path');
    var pathLength = 307.919; // Pre-calculated path length for radius 49

    if ($backToTop.length) {
        $progressPath.css({
            'transition': 'none',
            'strokeDasharray': pathLength + ' ' + pathLength,
            'strokeDashoffset': pathLength
        });

        var updateProgress = function() {
            var scroll = $(window).scrollTop();
            var height = $(document).height() - $(window).height();
            var progress = pathLength - (scroll * pathLength / height);
            $progressPath.css('strokeDashoffset', progress);

            if (scroll > 300) {
                $backToTop.addClass('is-active');
            } else {
                $backToTop.removeClass('is-active');
            }
        };

        $(window).on('scroll', updateProgress);
        
        $backToTop.on('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Run once on load
        updateProgress();
    }

    // Header Scroll Effect
    $(window).on('scroll', function() {
        if ($(window).scrollTop() > 30) {
            $header.addClass('scrolled');
        } else {
            $header.removeClass('scrolled');
        }
    });

    // Initial check for scroll
    if ($(window).scrollTop() > 30) {
        $header.addClass('scrolled');
    }
});