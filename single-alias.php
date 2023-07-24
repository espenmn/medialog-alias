<?php
/**
 * The template used for redirecting to external url
 *
 * @package medialog-alias
 */
$lenke = get_post_meta($post->ID, "_external_url", true);

if ( ! current_user_can( 'edit_post', $post->ID ) ) {
     header("Location: $lenke ");
} else {

    get_header();
    echo '<div class="main-content-container container">';
    the_title( '<h1>', '</h1>' );
    the_excerpt();
    echo get_the_post_thumbnail( $post->ID, 'medium_large' );
    echo '<p>The link is <a href="' . $lenke . '">' . $lenke . '</a>';
    echo '<p>You see this because you can edit the page.<br/>';
    echo 'Other users are redirected to the url automaically</p>';
    echo '</div>';
}
?>
