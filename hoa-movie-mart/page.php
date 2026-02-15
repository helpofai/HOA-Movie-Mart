<?php get_header(); ?>

<div class="generic-page-wrapper">
    <div class="generic-content-container">
        <?php while ( have_posts() ) : the_post(); ?>
            <div class="page-header-simple">
                <h1 class="page-title"><?php the_title(); ?></h1>
            </div>
            
            <div class="page-content-body">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>
