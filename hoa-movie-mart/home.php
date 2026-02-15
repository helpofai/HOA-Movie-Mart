<?php get_header(); ?>

<div class="blog-archive-wrapper">
    <div class="archive-header">
        <h1 class="archive-title">Cinematic Insights & News</h1>
        <p class="archive-subtitle">Stay updated with the latest in the world of movies and TV series.</p>
    </div>

    <div class="blog-main-container">
        <main class="blog-posts-grid">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <article class="blog-card">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="blog-card-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium_large'); ?>
                                <div class="blog-card-overlay">
                                    <span class="read-indicator">Read Article <i class="fas fa-arrow-right"></i></span>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="blog-card-content">
                        <div class="blog-card-meta">
                            <span class="blog-date"><i class="far fa-calendar-alt"></i> <?php echo get_the_date(); ?></span>
                            <span class="blog-category"><i class="far fa-folder-open"></i> <?php the_category(', '); ?></span>
                        </div>
                        <h2 class="blog-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="blog-card-excerpt">
                            <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
                        </div>
                        <div class="blog-card-footer">
                            <a href="<?php the_permalink(); ?>" class="btn-read-more">Continue Reading</a>
                            <span class="blog-author">By <?php the_author(); ?></span>
                        </div>
                    </div>
                </article>
            <?php endwhile; else : ?>
                <div class="no-results-box">
                    <i class="fas fa-search"></i>
                    <h3>No articles found</h3>
                    <p>It seems we haven't published anything here yet. Check back soon!</p>
                </div>
            <?php endif; ?>

            <div class="pagination-wrapper">
                <?php the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => '<i class="fas fa-chevron-left"></i>',
                    'next_text' => '<i class="fas fa-chevron-right"></i>',
                ) ); ?>
            </div>
        </main>

        <aside class="blog-sidebar-area">
            <?php get_sidebar(); ?>
        </aside>
    </div>
</div>

<?php get_footer(); ?>
