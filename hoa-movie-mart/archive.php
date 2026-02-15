<?php get_header(); ?>

<div class="blog-single-wrapper">
    <div class="archive-header-section">
        <div class="section-header">
            <h1 class="section-title">
                <span style="opacity: 0.6; font-size: 0.9rem; display: block; text-transform: uppercase; margin-bottom: 5px;">Browsing Archive</span>
                <?php the_archive_title(); ?>
            </h1>
        </div>
        <?php if ( get_the_archive_description() ) : ?>
            <div class="archive-description"><?php the_archive_description(); ?></div>
        <?php endif; ?>
    </div>

    <div class="blog-container">
        <main class="blog-main">
            <div class="movie-grid" style="grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
                    get_template_part( 'template-parts/content', 'movie' );
                ?>
                <?php endwhile; else : ?>
                    <div class="no-results">
                        <i class="fas fa-film"></i>
                        <h3>No content found</h3>
                    </div>
                <?php endif; ?>
            </div>

            <div class="pagination">
                <?php the_posts_pagination(); ?>
            </div>
        </main>

        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>