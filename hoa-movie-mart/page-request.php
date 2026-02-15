<?php
/**
 * Template Name: Request Page
 */
get_header(); ?>

<div class="generic-page-wrapper">
    <div class="generic-content-container">
        <div class="page-header-simple">
            <h1 class="page-title">Request a Movie or Series</h1>
            <p style="color:var(--text-muted); margin-top:10px;">Can't find what you're looking for? Submit a request below and we'll upload it ASAP!</p>
        </div>

        <div class="request-form-wrapper">
            <?php echo do_shortcode('[movie_request_form]'); ?>
        </div>
        
        <div class="page-content-extra" style="margin-top:30px;">
            <?php the_content(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>