<?php
/**
 * HOA Custom Widgets
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 1. Latest Movies Widget
 */
class HOA_Widget_Latest_Movies extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'hoa_latest_movies',
            __( 'HOA - Latest Movies', 'helpofai' ),
            array( 'description' => __( 'Displays a list of recent movies with thumbnails.', 'helpofai' ) )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $query = new WP_Query( array(
            'post_type'      => 'movie',
            'posts_per_page' => ! empty( $instance['count'] ) ? $instance['count'] : 5,
            'orderby'        => 'date',
            'order'          => 'DESC'
        ) );

        if ( $query->have_posts() ) {
            echo '<ul class="hoa-widget-list">';
            while ( $query->have_posts() ) {
                $query->the_post();
                $thumb = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) ?: get_post_meta( get_the_ID(), '_movie_poster_external', true );
                $rating = get_post_meta( get_the_ID(), '_movie_imdb_rating', true );
                ?>
                <li class="hoa-widget-item">
                    <a href="<?php the_permalink(); ?>" class="hoa-widget-thumb">
                        <img src="<?php echo esc_url( $thumb ? $thumb : 'https://via.placeholder.com/60x90' ); ?>" alt="<?php the_title(); ?>">
                    </a>
                    <div class="hoa-widget-content">
                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <span class="hoa-widget-meta">
                            <?php if($rating): ?><i class="fas fa-star" style="color:#ffd700;"></i> <?php echo esc_html($rating); ?><?php endif; ?>
                            <span><?php echo get_the_date('Y'); ?></span>
                        </span>
                    </div>
                </li>
                <?php
            }
            echo '</ul>';
            wp_reset_postdata();
        }
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Latest Movies', 'helpofai' );
        $count = ! empty( $instance['count'] ) ? $instance['count'] : 5;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'helpofai' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php _e( 'Number of movies:', 'helpofai' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $count ); ?>" size="3">
        </p>
        <?php
    }
}

/**
 * 2. Recent Blog Posts Widget (Visual)
 */
class HOA_Widget_Recent_Posts extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'hoa_recent_posts',
            __( 'HOA - Visual Recent Posts', 'helpofai' ),
            array( 'description' => __( 'Displays recent blog posts with images.', 'helpofai' ) )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $query = new WP_Query( array(
            'post_type'      => 'post',
            'posts_per_page' => ! empty( $instance['count'] ) ? $instance['count'] : 5,
            'ignore_sticky_posts' => true
        ) );

        if ( $query->have_posts() ) {
            echo '<ul class="hoa-widget-list">';
            while ( $query->have_posts() ) {
                $query->the_post();
                ?>
                <li class="hoa-widget-item">
                    <?php if(has_post_thumbnail()): ?>
                    <a href="<?php the_permalink(); ?>" class="hoa-widget-thumb">
                        <?php the_post_thumbnail('thumbnail'); ?>
                    </a>
                    <?php endif; ?>
                    <div class="hoa-widget-content">
                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <span class="hoa-widget-meta"><?php echo get_the_date(); ?></span>
                    </div>
                </li>
                <?php
            }
            echo '</ul>';
            wp_reset_postdata();
        }
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Recent Posts', 'helpofai' );
        $count = ! empty( $instance['count'] ) ? $instance['count'] : 5;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'helpofai' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php _e( 'Count:', 'helpofai' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $count ); ?>" size="3">
        </p>
        <?php
    }
}

/**
 * 3. Genres / Taxonomy Widget
 */
class HOA_Widget_Taxonomy_List extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'hoa_taxonomy_list',
            __( 'HOA - Genres / Categories', 'helpofai' ),
            array( 'description' => __( 'Displays a list of Movie Genres or Post Categories.', 'helpofai' ) )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $taxonomy = ! empty( $instance['taxonomy'] ) ? $instance['taxonomy'] : 'movie_genre';
        $terms = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => true ) );

        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            echo '<div class="hoa-tag-cloud">';
            foreach ( $terms as $term ) {
                echo '<a href="' . esc_url( get_term_link( $term ) ) . '" class="hoa-tag-item">' . esc_html( $term->name ) . '</a>';
            }
            echo '</div>';
        }
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Genres', 'helpofai' );
        $taxonomy = ! empty( $instance['taxonomy'] ) ? $instance['taxonomy'] : 'movie_genre';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'helpofai' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>"><?php _e( 'Taxonomy Slug:', 'helpofai' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'taxonomy' ) ); ?>">
                <option value="movie_genre" <?php selected( $taxonomy, 'movie_genre' ); ?>>Movie Genres</option>
                <option value="category" <?php selected( $taxonomy, 'category' ); ?>>Blog Categories</option>
                <option value="post_tag" <?php selected( $taxonomy, 'post_tag' ); ?>>Blog Tags</option>
                <option value="movie_year" <?php selected( $taxonomy, 'movie_year' ); ?>>Movie Years</option>
            </select>
        </p>
        <?php
    }
}

// Register Widgets
function hoa_register_custom_widgets() {
    register_widget( 'HOA_Widget_Latest_Movies' );
    register_widget( 'HOA_Widget_Recent_Posts' );
    register_widget( 'HOA_Widget_Taxonomy_List' );
}
add_action( 'widgets_init', 'hoa_register_custom_widgets' );
