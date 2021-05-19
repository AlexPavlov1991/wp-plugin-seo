<?php

if ( ! defined('WP_UNINSTALL_PLUGIN') )
	exit;

$all_posts = get_posts( [
	"post_type" => ["post", "page"]
	,"meta_key" => ["_ap_seo_title", "_ap_seo_description", "_ap_seo_keywords", "_ap_seo_noindex"]
] );

foreach ($all_posts as $post)
{
	delete_post_meta( $post->ID, "_ap_seo_title" );
	delete_post_meta( $post->ID, "_ap_seo_description" );
	delete_post_meta( $post->ID, "_ap_seo_keywords" );
	delete_post_meta( $post->ID, "_ap_seo_noindex" );
}

?>