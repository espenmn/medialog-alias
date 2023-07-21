<?php
/**
 * The template used for redirecting to external url
 *
 * @package medialog-alias
 */
$lenke = get_post_meta($post->ID, "_external_url", true);
header("Location: $lenke ");
?>
