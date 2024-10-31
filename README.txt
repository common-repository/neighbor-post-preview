=== Neighbor Post Preview ===
Contributors: thinlight
Tags: navigation, preview
Requires at least: 1.5
Tested up to: 2.3.3
Stable tag: 1.1.0

This gives you a preview when you hover on the previous/next links in a single post page.

== Description ==

Using the functions provided by this plugin, when you hover on the previous/next links in a single post page, you can see a preview of the post.

It shows an excerpt of the post. The excerpt is generated using the same mechanism as "[the_excerpt](http://codex.wordpress.org/Template_Tags/the_excerpt)" tag. If there is an explicit excerpt for the post, the explicit excerpt will be used. If the post doesn't have one, an excerpt is faked by truncating the full content.

If a post is password-protected, no preview will generated for it.

== Installation ==

1. Download the plugin and extract it
1. Put the plugin folder (`neighbor-post-preview`) into your WordPress plugin directory
1. Activate the plugin through the “Plugins” menu in WordPress admin area
1. Modify single.php file of your current theme. Find `previous_post_link(…)` and replace the function name by `tlnpp_previous_post_link`. Also replace `function next_post_link` by `tlnpp_next_post_link`

== Customization ==

Go to Neighbor Post Preview Options page in WordPress admin area (Options -> Neighbor Post Preview) and you’ll see 3 options to customize:

* *Excerpt length*. How many words do you want to display when there is not an explicit excerpt?
* *Delay before hide*. How long will the preview stay after your mouse leaves the link?
* *CSS class name of preview window*. You can customize the look of the preview window.
* *Show post title in preview window*. Many users just use "previous post/next post" as navigation link text, and this option will be very useful for them.
