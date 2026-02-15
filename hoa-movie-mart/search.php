<?php get_header(); ?>

<div class="archive-header-section">
    <div class="section-header">
        <h1 class="section-title">
            <span style="opacity: 0.6; font-size: 0.9rem; display: block; text-transform: uppercase; margin-bottom: 5px;">Search Results for</span>
            "<?php echo get_search_query(); ?>"
        </h1>
    </div>
</div>

<div class="archive-navigation">
    <div class="filter-bar">
        <div class="filter-left">
            <span class="filter-label">Sort Search:</span>
            <div class="filter-items">
                <a href="<?php echo esc_url(add_query_arg('orderby', 'date')); ?>" class="filter-item">Newest</a>
                <a href="<?php echo esc_url(add_query_arg('orderby', 'relevance')); ?>" class="filter-item">Most Relevant</a>
            </div>
        </div>
        <div class="filter-right">
            <form role="search" method="get" class="premium-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <div class="search-input-group">
                    <i class="fas fa-search search-icon"></i>
                    <input type="search" name="s" class="search-field" placeholder="Search again..." value="<?php echo get_search_query(); ?>">
                </div>
            </form>
        </div>
    </div>
</div>

<div class="movie-grid">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
        get_template_part( 'template-parts/content', 'movie' );
    ?>
    <?php endwhile; else : ?>
        <div class="no-results">
            <i class="fas fa-search"></i>
            <h3>No results found</h3>
            <p>We couldn't find any movies matching "<?php echo get_search_query(); ?>". Try different keywords.</p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-download" style="margin-top:20px;">Back to Home</a>
        </div>
    <?php endif; ?>
</div>

<div class="pagination">
    <?php the_posts_pagination(); ?>
</div>

<?php get_footer(); ?>
