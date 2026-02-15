<?php get_header(); ?>

<?php 
// 1. Featured Hero Slider
$hero_query = new WP_Query( array(
    'post_type'      => 'movie',
    'posts_per_page' => 5,
    'orderby'        => 'date',
    'order'          => 'DESC'
));

if ( $hero_query->have_posts() ) : ?>
    <section class="hero-slider swiper">
        <div class="swiper-wrapper">
            <?php while ( $hero_query->have_posts() ) : $hero_query->the_post();
                $backdrop = get_the_post_thumbnail_url( get_the_ID(), 'full' ) ?: get_post_meta( get_the_ID(), '_movie_poster_external', true );
                if ( ! $backdrop ) $backdrop = 'https://images.unsplash.com/photo-1485846234645-a62644f84728?q=80&w=2059&auto=format&fit=crop';
                $rating   = get_post_meta( get_the_ID(), '_movie_imdb_rating', true );
            ?>
                <div class="swiper-slide hero-slide">
                    <div class="hero-bg-image" style="background-image: url('<?php echo esc_url( $backdrop ); ?>');"></div>
                    <div class="hero-overlay"></div>
                    <div class="hero-content">
                        <span class="hero-badge"><i class="fas fa-fire"></i> Featured Selection</span>
                        <h2 class="hero-title"><?php the_title(); ?></h2>
                        <div class="hero-meta">
                            <?php if ( $rating ) : ?>
                                <span class="hero-rating"><i class="fas fa-star"></i> <?php echo esc_html( $rating ); ?></span>
                            <?php endif; ?>
                            <span class="meta-dot"></span>
                            <span><?php echo strip_tags( get_the_term_list( get_the_ID(), 'movie_year', '', ', ', '' ) ); ?></span>
                            <span class="meta-dot"></span>
                            <span><?php echo strip_tags( get_the_term_list( get_the_ID(), 'movie_genre', '', ', ', '' ) ); ?></span>
                        </div>
                        <p class="hero-description"><?php echo wp_trim_words( get_the_excerpt(), 25 ); ?></p>
                        <div class="hero-actions">
                            <a href="<?php the_permalink(); ?>" class="btn-play"><i class="fas fa-play"></i> Watch Now</a>
                            <a href="<?php echo esc_url( home_url('/request/') ); ?>" class="btn-request"><i class="fas fa-paper-plane"></i> Request Movie</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </section>
<?php endif; ?>

<!-- 2. Advanced Filter Bar -->
<div class="hoa-filter-strip">
    <div class="filter-strip-container">
        <div class="filter-group">
            <span class="filter-label"><i class="fas fa-sort-amount-down"></i> Sort By:</span>
            <div class="filter-pills">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="filter-pill <?php echo !isset($_GET['orderby']) ? 'active' : ''; ?>">Latest</a>
                <a href="<?php echo esc_url(add_query_arg('orderby', 'popular', home_url('/'))); ?>" class="filter-pill <?php echo (isset($_GET['orderby']) && $_GET['orderby'] == 'popular') ? 'active' : ''; ?>">Popular</a>
                <a href="<?php echo esc_url(add_query_arg('orderby', 'meta_value_num', home_url('/'))); ?>" class="filter-pill <?php echo (isset($_GET['orderby']) && $_GET['orderby'] == 'meta_value_num') ? 'active' : ''; ?>">Top Rated</a>
            </div>
        </div>
        <div class="filter-search-wrapper">
            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <div class="filter-search-input">
                    <input type="search" name="s" placeholder="Search our database..." value="<?php echo get_search_query(); ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="homepage-categories-section">
    <?php hoa_movie_taxonomy_bar( 'movie_genre', 'Genres' ); ?>
</div>

<!-- 3. Trending Now (Latest) -->
<?php 
$trending_args = array('post_type' => 'movie', 'posts_per_page' => 10, 'orderby' => 'date', 'order' => 'DESC');
if ( wp_count_posts('movie')->publish > 5 ) $trending_args['offset'] = 5;
$trending = new WP_Query( $trending_args );
if ( $trending->have_posts() ) : ?>
<div class="section-header">
    <h2 class="section-title">Trending Now</h2>
    <a href="<?php echo esc_url( get_post_type_archive_link('movie') ); ?>" class="view-all">View All <i class="fas fa-chevron-right"></i></a>
</div>
<div class="movie-row-slider swiper" id="trending-slider">
    <div class="swiper-wrapper">
        <?php $rank = 1; while ( $trending->have_posts() ) : $trending->the_post(); ?>
            <div class="swiper-slide"><?php get_template_part( 'template-parts/content', 'movie', array('rank' => $rank++) ); ?></div>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <div class="swiper-button-next"></div><div class="swiper-button-prev"></div>
</div>
<?php endif; ?>

<!-- 4. Discovery: Random Movies (Surprise Me) -->
<div class="section-header" style="margin-top: 60px;">
    <h2 class="section-title"><i class="fas fa-dice" style="color:var(--primary-color); margin-right:10px;"></i> Surprise Me</h2>
    <a href="<?php echo esc_url( home_url( '/?orderby=rand' ) ); ?>" class="view-all">Shuffle <i class="fas fa-sync-alt"></i></a>
</div>
<div class="movie-grid" style="margin-bottom: 60px;">
    <?php 
    $random_movies = new WP_Query( array(
        'post_type'      => 'movie',
        'posts_per_page' => 6,
        'orderby'        => 'rand'
    ));
    if ( $random_movies->have_posts() ) : while ( $random_movies->have_posts() ) : $random_movies->the_post(); 
        get_template_part( 'template-parts/content', 'movie' );
    endwhile; wp_reset_postdata(); endif; ?>
</div>

<!-- 5. Main Releases -->
<div class="section-header">
    <h2 class="section-title">Latest Releases</h2>
</div>
    <div class="movie-grid">
        <?php 
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $main_grid = new WP_Query( array(
            'post_type'      => 'movie', 
            'posts_per_page' => 12,
            'paged'          => $paged,
            'orderby'        => array('meta_value_num' => 'DESC', 'date' => 'DESC'),
            'meta_query'     => array('relation' => 'OR', array('key' => '_hoa_download_count', 'compare' => 'EXISTS'), array('key' => '_hoa_download_count', 'compare' => 'NOT EXISTS'))
        ));
        
        if ( $main_grid->have_posts() ) : while ( $main_grid->have_posts() ) : $main_grid->the_post(); 
            get_template_part( 'template-parts/content', 'movie', array('context' => 'grid') );
        endwhile; 
        ?>
    </div>

    <!-- Numeric Pagination -->
    <div class="pagination-wrapper" style="margin-top: 50px;">
        <?php 
        echo paginate_links( array(
            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'total'        => $main_grid->max_num_pages,
            'current'      => max( 1, get_query_var( 'paged' ) ),
            'format'       => '?paged=%#%',
            'show_all'     => false,
            'type'         => 'plain',
            'end_size'     => 2,
            'mid_size'     => 1,
            'prev_next'    => true,
            'prev_text'    => sprintf( '<i></i> %1$s', __( 'Previous', 'helpofai' ) ),
            'next_text'    => sprintf( '%1$s <i></i>', __( 'Next', 'helpofai' ) ),
            'add_args'     => false,
            'add_fragment' => '',
        ) );
        ?>
    </div>

    <?php 
    else:
        // Fallback: If no movies exist, show latest blog posts so the site isn't empty
        $blog_fallback = new WP_Query( array('post_type' => 'post', 'posts_per_page' => 6) );
        while ( $blog_fallback->have_posts() ) : $blog_fallback->the_post();
            echo '<div class="fallback-post" style="background:var(--bg-surface); padding:20px; border-radius:10px; border:var(--glass-border);">';
            echo '<h3><a href="'.get_permalink().'">'.get_the_title().'</a></h3>';
            the_excerpt();
            echo '</div>';
        endwhile; wp_reset_postdata();
    endif; wp_reset_postdata(); ?>
    </div>
</div>

<!-- Request Movie CTA Section -->
<section class="hoa-request-cta">
    <div class="cta-overlay"></div>
    <div class="cta-content">
        <div class="cta-icon-box">
            <i class="fas fa-bullhorn"></i>
        </div>
        <div class="cta-text">
            <h3>Can't find what you're looking for?</h3>
            <p>Our library is updated daily. If a specific movie or series is missing, let us know and we'll add it for you!</p>
        </div>
        <div class="cta-action">
            <a href="<?php echo esc_url( home_url('/request/') ); ?>" class="btn-play">Request Content</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
