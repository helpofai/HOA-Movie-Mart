<?php get_header(); ?>

<div class="blog-single-wrapper">
    <div class="blog-container">
        <main class="blog-main">
            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="blog-featured-image">
                            <?php the_post_thumbnail( 'large' ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="blog-content-inner">
                        <header class="blog-header">
                            <div class="blog-meta">
                                <span><i class="far fa-calendar"></i> <?php echo get_the_date(); ?></span>
                                <span><i class="far fa-user"></i> <?php the_author(); ?></span>
                                <span><i class="far fa-folder"></i> <?php the_category( ', ' ); ?></span>
                            </div>
                            <h1 class="blog-title"><?php the_title(); ?></h1>
                        </header>

                        <div class="blog-body">
                            <?php the_content(); ?>
                        </div>

                        <footer class="blog-footer">
                            <?php the_tags( '<span class="tags-links"><i class="fas fa-tags"></i> ', ', ', '</span>' ); ?>
                        </footer>
                    </div>

                    <?php 
                    // Comments
                    if ( comments_open() || get_comments_number() ) {
                        comments_template();
                    }
                    ?>
                </article>
            <?php endwhile; ?>
        </main>

        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>
