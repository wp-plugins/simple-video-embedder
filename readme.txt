=== Simple Video Embedder ===
Contributors: James Lao, Jason Schuller
Donate link: http://jameslao.com/
Tags: video, embed, youtube, vimeo, automatic, simple
Requires at least: 2.6
Tested up to: 2.8.2
Stable tag: 1.4

Adds a widget to the posting screen that makes posting videos a cinch.

== Description ==

Easily embed video within your posts. Adds a widget to the posting screen that enables you to post videos by simply providing the URL to the video hosted on common video sharing websites.

Note: This plugin requires PHP5.

Sites supported:

*   YouTube (HD)
*   Vimeo
*   MetaCafe
*   Seesmic
*   Google Video
*   Revver
*   JW media player

The widget also provides a space to add custom embed code for posting videos from unsupported sites.

== Changelog ==

1.4:

*   Fix for transparency issues.

1.3:

*   Admin notice if you are using PHP4.
*   Updated JW Player code so that the ID on the videoContainer div does not conflict if there are multiple players on the page.
*   Added a p75HasVideo(post_id) function that checks to see if a given post has a video.

1.2:

*   XMLRPC identification

1.1.1:

*   Autoplay support for YouTube.

1.1:

*   An easy to use API for adding support for new video sharing sites. See `video-embedder.php` for details.
*   An options page setting many default values including width and height of the player.
*   Support for specifying a flashvars string for JW media player.

== Installation ==

1. Upload the plugin file to your `wp-plugins/` folder.
2. Go the plugin management page and activate the plugin.
3. Add the `p75GetVideo(int $postID)` to your theme.
4. Go to Settings > Video Options to configure default values.

== Frequently Asked Questions ==

= How do I post a video? =

Go to the posting screen and add the URL of the video.

= What about sites that are not supported? =

Just paste the embed code into the embed code form.

= How can I make YouTube videos autoplay? =

Append "&autoplay=1" to your video URL.

