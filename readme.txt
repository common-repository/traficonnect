=== Traficonnect ===
Contributors: Traficode
Tags: SEO, Rank Math, Yoast, REST API
Requires at least: 5.0
Tested up to: 6.6.2
Requires PHP: 7.0
Stable tag: 1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Traficonnect adds custom SEO meta fields to the default WordPress REST API response

== Description ==
Traficonnect adds custom SEO meta fields including focus keywords to the default WordPress REST API response for posts. It supports both Rank Math and Yoast SEO plugins.

== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/traficonnect` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.

== Changelog ==
= 1.1 =
* Initial release with Yoast and Rank Math SEO integration via REST API.

== Frequently Asked Questions ==
= How do I retrieve the SEO meta fields via REST API? =
Simply make a GET request to the `/wp-json/wp/v2/posts/<post_id>` endpoint and check for the `traficonnect_seo_meta` field.

= Can I update SEO meta fields via the REST API? =
Yes, you can include the `traficonnect_seo_meta` fields in the POST or PUT requests, provided you have the required permissions.
