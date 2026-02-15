<?php
/**
 * The template for displaying comments/reviews
 */

if ( post_password_required() ) return;
?>

<div id="comments" class="movie-comments-area">

    <?php if ( have_comments() ) : ?>
        <h3 class="section-heading">
            <?php echo get_comments_number() . ' Reviews'; ?>
        </h3>

        <ul class="comment-list">
            <?php
            wp_list_comments( array(
                'style'      => 'ul',
                'short_ping' => true,
                'avatar_size' => 50,
            ) );
            ?>
        </ul>

        <?php the_comments_navigation(); ?>
    <?php endif; ?>

    <?php
    // Custom Comment Form with Star Rating
    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );

    $fields = array(
        'author' => '<div class="comment-form-flex"><div class="comment-field"><label>Name</label><input name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>',
        'email'  => '<div class="comment-field"><label>Email</label><input name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></div></div>',
    );

    $args = array(
        'fields' => $fields,
        'comment_field' => '
            <div class="rating-selector" style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:10px;">Your Rating:</label>
                <div class="star-rating">
                    <input type="radio" name="rating" value="5" id="5"><label for="5">☆</label>
                    <input type="radio" name="rating" value="4" id="4"><label for="4">☆</label>
                    <input type="radio" name="rating" value="3" id="3"><label for="3">☆</label>
                    <input type="radio" name="rating" value="2" id="2"><label for="2">☆</label>
                    <input type="radio" name="rating" value="1" id="1"><label for="1">☆</label>
                </div>
            </div>
            <div class="comment-field">
                <label>Review</label>
                <textarea name="comment" cols="45" rows="5" aria-required="true"></textarea>
            </div>',
        'class_submit' => 'btn-download',
        'label_submit' => 'Post Review',
        'title_reply' => 'Write a Review',
    );

    comment_form($args);
    ?>

</div>

<style>
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 5px;
    }
    .star-rating input { display: none; }
    .star-rating label {
        font-size: 2rem;
        color: #444;
        cursor: pointer;
        transition: color 0.2s;
    }
    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        color: #ffd700;
    }
    .star-rating label:hover:before,
    .star-rating label:hover ~ label:before,
    .star-rating input:checked ~ label:before {
        content: '★';
        position: absolute;
    }
</style>
