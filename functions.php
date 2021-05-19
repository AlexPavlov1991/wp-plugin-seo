<?php

function ap_seo_admin_scripts($hook)
{
	wp_enqueue_style("ap-favorites-admin-style", plugins_url("/css/ap-seo-admin-style.css", __FILE__), null, null);
}

// Генерация html кода
function ap_seo_meta_tags()
{
	$post = get_post();
	$blog_public = get_option("blog_public");

	print "\n<!-- SEO plugin by Alex Pavlov -->\n";

	// title
	$ap_seo_title = get_post_meta($post->ID, "_ap_seo_title", true);
	if ($ap_seo_title) {
		$title = $ap_seo_title;
	} else if (is_front_page()) {
		$title = get_bloginfo("name");
	} else {
		$title = wp_title("|", false, "right") . get_bloginfo("name");
	}
	print "<title>" . $title . "</title>\n";

	// description
	$ap_seo_description = get_post_meta($post->ID, "_ap_seo_description", true);
	if ($ap_seo_description) {
		$description = $ap_seo_description;
	} else if (is_front_page()) {
		$description = get_bloginfo("description");
	} else {
		$description = $title;
	}
	print "<meta name='description' content='" . $description . "'>\n";

	// keywords
	$ap_seo_keywords = get_post_meta($post->ID, "_ap_seo_keywords", true);
	$keywords = ($ap_seo_keywords) ? $ap_seo_keywords : "";
	print "<meta name='keywords' content='" . $keywords . "'>\n";

	// robots -> noindex, nofollow
	$ap_seo_noindex = get_post_meta($post->ID, "_ap_seo_noindex", true);
	print ($ap_seo_noindex == "on" || $blog_public == 0) ? "<meta name='robots' content='noindex, nofollow'>\n" : "";

	print "<!-- SEO plugin -->\n\n";

}

function ap_seo_meta_boxes()
{
	add_meta_box("ap-seo-box", "SEO", "ap_seo_print_box", ["post", "page"], "normal", "high");
}

function ap_seo_print_box($post)
{
	wp_nonce_field( basename( __FILE__ ), 'ap_seo_metabox_nonce' );

	// title
	$ap_seo_title = get_post_meta($post->ID, '_ap_seo_title',true);
	$html = "<label for='_ap_seo_title'>Заголовок (title):</label>";
	$html .= "<div class='ap-seo-value-div'><input id='_ap_seo_title' type='text' name='_ap_seo_title' value='" . $ap_seo_title . "' /></div>";
	
	// description
	$ap_seo_description = get_post_meta($post->ID, '_ap_seo_description',true);
	$html .= "<label for='_ap_seo_description'>Описание (description):</label>";
	$html .= "<div class='ap-seo-value-div'><textarea id='_ap_seo_description' name='_ap_seo_description'>" . $ap_seo_description . "</textarea></div>";
	
	// keywords
	$ap_seo_keywords = (string) get_post_meta($post->ID, '_ap_seo_keywords',true);
	$html .= "<label for='_ap_seo_keywords'>Ключевые слова (keywords):</label>";
	$html .= "<div class='ap-seo-value-div'><input id='_ap_seo_keywords' type='text' name='_ap_seo_keywords' value='" . $ap_seo_keywords . "' /></div>";

	// robots -> noindex, nofollow
	$ap_seo_noindex = get_post_meta($post->ID, '_ap_seo_noindex',true);
	$html .= "<label for='_ap_seo_noindex'>Запретить индексацию страницы?</label><input id='_ap_seo_noindex' type='checkbox' name='_ap_seo_noindex'";
	$html .= (get_post_meta($post->ID, "_ap_seo_noindex",true) == "on") ? " checked='checked'" : "";
	$html .= ">";
 
	print $html;
}

function ap_seo_save_box_data($post_id)
{
	// проверяем, пришёл ли запрос со страницы с метабоксом
	if ( !isset( $_POST["ap_seo_metabox_nonce"] )
	|| !wp_verify_nonce( $_POST["ap_seo_metabox_nonce"], basename( __FILE__ ) ) )
        return $post_id;
	// проверяем, является ли запрос автосохранением
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;
	// проверяем, права пользователя, может ли он редактировать записи
	if ( !current_user_can( 'edit_post', $post_id ) )
		return $post_id;
	// теперь также проверим тип записи	
	$post = get_post($post_id);
	if ( in_array($post->post_type, ["post", "page"]) ) {
		update_post_meta( $post_id, '_ap_seo_title', esc_attr(trim($_POST['_ap_seo_title'])) );
		update_post_meta( $post_id, '_ap_seo_description', esc_attr(trim($_POST['_ap_seo_description'])) );
		update_post_meta( $post_id, '_ap_seo_keywords', esc_attr(trim($_POST['_ap_seo_keywords'])) );
		update_post_meta( $post_id, '_ap_seo_noindex', esc_attr(trim($_POST['_ap_seo_noindex'])) );
	}
	return $post_id;
}

?>