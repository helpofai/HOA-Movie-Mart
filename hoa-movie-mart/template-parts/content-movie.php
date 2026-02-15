<?php
/**
 * Template part for displaying movie card in grid
 * Supports 'rank', 'context' arguments
 */

$imdb_rating = get_post_meta( get_the_ID(), '_movie_imdb_rating', true );
$year_terms = get_the_terms( get_the_ID(), 'movie_year' );
$year = $year_terms ? $year_terms[0]->name : '';
$downloads = get_post_meta( get_the_ID(), '_hoa_download_count', true );

// Context Variables
$rank = isset($args['rank']) ? $args['rank'] : false;
$context = isset($args['context']) ? $args['context'] : '';
$card_class = $rank ? 'movie-card trending-card' : 'movie-card';
?>
<article class="<?php echo esc_attr($card_class); ?>" data-post-id="<?php the_ID(); ?>">
    <?php if ( $rank ) : ?>
        <span class="rank-number"><?php echo esc_html($rank); ?></span>
    <?php endif; ?>

    <a href="<?php the_permalink(); ?>">
        <?php if ( $context === 'coming-soon' ) : ?>
            <div class="ribbon-coming-soon">Soon</div>
        <?php endif; ?>
        
        <?php if ( $downloads > 100 && $context === 'grid' ) : ?>
            <div class="badge-hot" title="Trending"><i class="fas fa-fire"></i></div>
        <?php endif; ?>

        <?php 
        if ( function_exists('hoa_movie_poster') ) {
            hoa_movie_poster( 'medium', 'movie-poster' );
        } else {
            the_post_thumbnail( 'medium', array( 'class' => 'movie-poster' ) );
        }
        ?>
    </a>
    <div class="movie-info">
        <h2 class="movie-title">
            <a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;"><?php the_title(); ?></a>
        </h2>
        <div class="movie-meta">
            <span><?php echo esc_html( $year ); ?></span>
            <?php if ( $imdb_rating ) : ?>
                <span class="rating"><i class="fas fa-star"></i> <?php echo esc_html( $imdb_rating ); ?></span>
            <?php endif; ?>
        </div>
    </div>
</article>
