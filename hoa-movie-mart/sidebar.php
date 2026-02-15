<aside class="blog-sidebar">
    <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
    <?php else : ?>
        <!-- Default Fallback Widgets -->
        <div class="widget">
            <h3 class="widget-title">Recent Posts</h3>
            <ul>
                <?php 
                $recent = new WP_Query( array( 'posts_per_page' => 5, 'ignore_sticky_posts' => 1 ) );
                while( $recent->have_posts() ) : $recent->the_post(); ?>
                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                <?php endwhile; wp_reset_postdata(); ?>
            </ul>
        </div>
        <div class="widget">
            <h3 class="widget-title">Categories</h3>
            <ul>
                <?php wp_list_categories( array( 'title_li' => '' ) ); ?>
            </ul>
        </div>
    <?php endif; ?>
</aside>
