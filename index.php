<?php
/*
Plugin Name: SEO
Description: Создание title, description и keywords для станиц и постов сайта. Так же возможность скрытия от индексации.
Version: 1.0
Author: Александр Павлов | alexpavlov.it@gmail.com
*/

require __DIR__ . "/functions.php";

remove_action( "wp_head", "wp_robots", 1 );
remove_action( "wp_head", "_wp_render_title_tag", 1 );

add_action( "admin_enqueue_scripts", "ap_seo_admin_scripts" );
add_action( "wp_head", "ap_seo_meta_tags" );
add_action( "admin_menu", "ap_seo_meta_boxes" );
add_action( "save_post", "ap_seo_save_box_data" );

?>