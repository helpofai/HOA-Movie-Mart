<?php get_header(); ?>

<div class="blog-archive-wrapper">
    <div class="archive-header">
        <h1 class="archive-title">
            <?php 
            if ( is_category() ) {
                single_cat_title();
            } elseif ( is_tag() ) {
                single_tag_title();
            } elseif ( is_author() ) {
                the_author();
            } elseif ( is_search() ) {
                printf( 'Search Results for: %s', get_search_query() );
            } else {
                echo 'Archives';
            }
            ?>
        </h1>
        <?php if ( get_the_archive_description() ) : ?>
            <div class="archive-subtitle"><?php the_archive_description(); ?></div>
        <?php endif; ?>
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
                                    <span class="read-indicator">Read More <i class="fas fa-arrow-right"></i></span>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="blog-card-content">
                        <div class="blog-card-meta">
                            <span class="blog-date"><i class="far fa-calendar-alt"></i> <?php echo get_the_date(); ?></span>
                            <?php if ( has_category() ) : ?>
                                <span class="blog-category"><i class="far fa-folder-open"></i> <?php the_category(', '); ?></span>
                            <?php endif; ?>
                        </div>
                        <h2 class="blog-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="blog-card-excerpt">
                            <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
                        </div>
                        <div class="blog-card-footer">
                            <a href="<?php the_permalink(); ?>" class="btn-read-more">View Post</a>
                        </div>
                    </div>
                </article>
            <?php endwhile; else : ?>
                <div class="no-results-box">
                    <i class="fas fa-search"></i>
                    <h3>Nothing found</h3>
                    <p>Try searching for something else or browse our latest movies.</p>
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