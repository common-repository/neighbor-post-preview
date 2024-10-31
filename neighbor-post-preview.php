<?php
/*
Plugin Name: Neighbor Post Preview
Plugin URI: http://thinlight.org/projects/wordpress-neighbor-post-preview/
Description: Enables preview when hovering on the previous/next links in a single post page.
Version: 1.1.0
Author: thinlight
Author URI: http://thinlight.org/
*/
/*  Copyright 2008 Thin Light (email : theguy@thinlight.org)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function tlnpp_excerpt($text, $excerpt_length = 55) {
	$text = str_replace(']]>', ']]&gt;', $text);
	$text = strip_tags($text);
	$words = explode(' ', $text, $excerpt_length + 1);
	if (count($words) > $excerpt_length) {
		array_pop($words);
		array_push($words, '[...]');
		$text = implode(' ', $words);
	}
	
	return apply_filters('the_excerpt', $text);
}

function tlnpp_previous_post_link($format='&laquo; %link', $link='%title', $in_same_cat = false, $excluded_categories = '') {
	
	if ( is_attachment() )
		$post = &get_post($GLOBALS['post']->post_parent);
	else
		$post = get_previous_post($in_same_cat, $excluded_categories);

	if ( !$post )
		return;
	$post = &get_post($post->ID);

	$title = $post->post_title;

	if ( empty($post->post_title) )
		$title = __('Previous Post');

	$title = apply_filters('the_title', $title, $post);
	$string = '<a id="previous-link" href="'.get_permalink($post->ID).'">';
	$link = str_replace('%title', $title, $link);
	$link = $pre . $string . $link . '</a>';

	$format = str_replace('%link', $link, $format);

	echo $format;
	
	if (empty($post->post_password)) {
		$tlnpp_excerpt_length = (int)get_option('tlnpp_excerpt_length');
		$excerpt = ($post->post_excerpt == '') ? (tlnpp_excerpt($post->post_content, $tlnpp_excerpt_length))
			: (apply_filters('the_excerpt', $post->post_excerpt));
		if (get_option('tlnpp_include_title') === "1") {
			$excerpt = "<strong>" . $title . "</strong><br />" . $excerpt;
		}
		if (trim($excerpt) != '') {
			$preview = '<span id="previous-link-preview" style="display:none;">'
				. $excerpt . '</span>';
			echo $preview;
		}
	}
}

function tlnpp_next_post_link($format='%link &raquo;', $link='%title', $in_same_cat = false, $excluded_categories = '') {

	$post = get_next_post($in_same_cat, $excluded_categories);

	if ( !$post )
		return;
	$post = &get_post($post->ID);

	$title = $post->post_title;

	if ( empty($post->post_title) )
		$title = __('Next Post');

	$title = apply_filters('the_title', $title, $post);
	$string = '<a id="next-link" href="'.get_permalink($post->ID).'">';
	$link = str_replace('%title', $title, $link);
	$link = $string . $link . '</a>';
	$format = str_replace('%link', $link, $format);

	echo $format;
	
	if (empty($post->post_password)) {
		$tlnpp_excerpt_length = (int)get_option('tlnpp_excerpt_length');
		$excerpt = ($post->post_excerpt == '') ? (tlnpp_excerpt($post->post_content, $tlnpp_excerpt_length))
			: (apply_filters('the_excerpt', $post->post_excerpt));
		if (get_option('tlnpp_include_title') === "1") {
			$excerpt = "<strong>" . $title . "</strong><br />" . $excerpt;
		}
		if (trim($excerpt) != '') {
			$preview = '<span id="next-link-preview" style="display:none;">'
				. $excerpt . '</span>';
			echo $preview;
		}
	}
}

function tlnpp_prepare_preview() {
	if (is_single()) {
		$tlnpp_hide_after = (float)get_option('tlnpp_hide_after');
		$tlnpp_preview_class = get_option('tlnpp_preview_class');
?>
<link rel="stylesheet" href="<?php echo get_option('siteurl'); ?>/wp-content/plugins/neighbor-post-preview/prototip.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/neighbor-post-preview/prototype.js"></script>
<script type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/neighbor-post-preview/prototip.js"></script>
<script type="text/javascript">
//<![CDATA[
function initPreview(prefix) {
if ($(prefix + '-link-preview'))
	new Tip(prefix + '-link', $(prefix + '-link-preview').innerHTML,
	{className:'<?php echo $tlnpp_preview_class; ?>',hook:{target:'bottomLeft',tip:'topLeft'},
		hideOn:false,hideAfter:<?php echo $tlnpp_hide_after; ?>});
}
document.observe('dom:loaded',
function() {
initPreview('previous');
initPreview('next');
});
//]]>
</script>
<?php
	}
}

// Add options when plugin is activated
function tlnpp_plugin_activate() {
	add_option('tlnpp_excerpt_length', 55);
	add_option('tlnpp_hide_after', 0.3);
	add_option('tlnpp_preview_class', 'silver');
}

function tlnpp_options() {
?>
<div class="wrap">
	<h2><?php _e('Neighbor Post Preview Options'); ?></h2>
	<form method="post" action="options.php">
		<?php wp_nonce_field('update-options') ?>
		<table width="100%" cellspacing="2" cellpadding="5" class="optiontable editform">
			<tbody>
				<tr valign="top">
					<th width="33%" scope="row">Length of the preview: </th>
					<td><input type="text" size="3" value="<?php form_option('tlnpp_excerpt_length') ?>" id="tlnpp_excerpt_length" name="tlnpp_excerpt_length" />
					words (In case there isn't an explicit excerpt.)</td>
				</tr>
				<tr valign="top">
					<th width="33%" scope="row">Hide the preview after: </th>
					<td><input type="text" size="3" value="<?php form_option('tlnpp_hide_after') ?>" id="tlnpp_hide_after" name="tlnpp_hide_after" />
					second(s)</td>
				</tr>
				<tr valign="top">
					<th width="33%" scope="row">CSS class name of preview window: </th>
					<td><input type="text" size="10" value="<?php form_option('tlnpp_preview_class') ?>" id="tlnpp_preview_class" name="tlnpp_preview_class" /></td>
				</tr>
				<tr valign="top">
					<td colspan="2" style="padding-left:50px;font-weight:bold;"><label for="tlnpp_include_title">
							<input type="checkbox" value="1" <?php checked("1", get_option('tlnpp_include_title')); ?> id="tlnpp_include_title" name="tlnpp_include_title" />
						Show post title in preview window
					</td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="tlnpp_excerpt_length,tlnpp_hide_after,tlnpp_preview_class,tlnpp_include_title" />
		<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Update Options &raquo;') ?>" />
		</p>
		<p>For more information, visit <a target="_blank" href="http://thinlight.org/projects/wordpress-neighbor-post-preview/">Neighbor Post Preview's homepage</a>.</p>
	</form>
</div>
<?php
}

function tlnpp_add_options() {
	add_options_page("Neighbor Post Preview Options", "Neighbor Post Preview", 10, basename(__FILE__), 'tlnpp_options');
}

register_activation_hook(__FILE__, 'tlnpp_plugin_activate');
add_action('wp_head', 'tlnpp_prepare_preview');
add_action('admin_menu', 'tlnpp_add_options');
?>