<?php
/*
Plugin Name: Simple Video Embedder
Plugin URI: http://www.press75.com/the-simple-video-embedder-wordpress-plugin/
Description: Easily embed video within your posts. Brought to you by <a href="http://www.press75.com" title="Press75.com">Press75.com</a>.
Version: 1.0
Author: James Lao
Author URI: http://jameslao.com/
*/

require_once('config.php');
require_once('video-embedder.php');

/**
 * Post admin hooks
 */
add_action('admin_menu', "p75_videoAdminInit");
add_action('save_post', 'p75_saveVideo');

function p75_videoAdminInit() {
	if( function_exists("add_meta_box") ) {
		add_meta_box("p75-video-posting", "Post Video Options", "p75_videoPosting", "post", "advanced");
	}
}

/**
 * Code for the meta box.
 */
function p75_videoPosting() {
	global $post_ID;
	$videoURL = get_post_meta($post_ID, '_videoembed', true);
	$videoHeight = get_post_meta($post_ID, '_videoheight', true);
	$videoWidth = get_post_meta($post_ID, '_videowidth', true);
	$videoEmbed = get_post_meta($post_ID, '_videoembed_manual', true);
	
?>

	<div style="float:left; margin-right: 5px;">
		<label for="p75-video-url">Video URL: <a href="http://www.press75.com/docs/simple-video-embedder/" title="View Supported Formats" target="_blank">Supported Formats</a></label><br />
		<input style="width: 300px; margin-top:5px;" type="text" id="p75-video-url" name="p75-video-url" value="<?php echo $videoURL; ?>" tabindex='100' />
	</div>
	<div style="float:left; margin-right: 5px;">
		<label for="p75-video-width3">Width:</label><br />
		<input style="margin-top:5px;" type="text" id="p75-video-width3" name="p75-video-width" size="4" value="<?php echo $videoWidth; ?>" tabindex='101' />
	</div>
	<div style="float:left;">
		<label for="p75-video-height4">Height:</label><br />
		<input style="margin-top:5px;" type="text" id="p75-video-height4" name="p75-video-height" size="4" value="<?php echo $videoHeight; ?>" tabindex='102' />
	</div>
	<div class="clear"></div>
	
	<div style="margin-top:10px;">
		  <label for="p75-video-embed">Embed Code: (Overrides Above Settings)</label><br />
		  <textarea style="width: 100%; margin:5px 2px 0 0;" id="p75-video-embed" name="p75-video-embed" rows="4" tabindex="103"><?php echo htmlspecialchars(stripslashes($videoEmbed)); ?></textarea>
	</div>
	<p style="margin:10px 0 0 0;">
		<input id="p75-remove-video" type="checkbox" name="p75-remove-video" /> <label for="p75-remove-video">Remove video</label>
	</p>

<?php
	if ( $videoURL ) {
		echo '<div style="margin-top:10px;">Video Preview: (Actual Size)<br /><div id="video_preview" style="padding: 3px; border: 1px solid #CCC;float: left; margin-top: 5px;">';
		$videoEmbedder = new p75VideoEmbedder($videoURL);
		$videoEmbedder->setDefaultWidth(P75_VIDEO_W);
		$videoEmbedder->setHeight($videoHeight);
		$videoEmbedder->setWidth($videoWidth);
		echo $videoEmbedder->getEmbedCode();
		echo '</div></div><div class="clear"></div>';
	} else if ( $videoEmbed ) {
		echo '<div style="margin-top:10px;">Video Preview: (Actual Size)<br /><div id="video_preview" style="padding: 3px; border: 1px solid #CCC;float: left; margin-top: 5px;">';
		echo stripslashes($videoEmbed);
		echo '</div></div><div class="clear"></div>';
	}
?>
<p style="margin:10px 0 0 0;"><input id="publish" class="button-primary" type="submit" value="Update Post" accesskey="p" tabindex="5" name="save"/></p>
<?php
	if ( $thumbURL )
		echo '<a href="' . $thumbURL . '" title="Preview Video" target="_blank">Preview Video</a>';
}

/**
 * Saves the thumbnail image as a meta field associated
 * with the current post. Runs when a post is saved.
 */
function p75_saveVideo( $postID ) {
	global $wpdb;

	// Get the correct post ID if revision.
	if ( $wpdb->get_var("SELECT post_type FROM $wpdb->posts WHERE ID=$postID")=='revision')
		$postID = $wpdb->get_var("SELECT post_parent FROM $wpdb->posts WHERE ID=$postID");

	// Trim white space just in case.
	$_POST['p75-video-embed'] = trim($_POST['p75-video-embed']);
	$_POST['p75-video-url'] = trim($_POST['p75-video-url']);
	$_POST['p75-video-width'] = trim($_POST['p75-video-width']);
	$_POST['p75-video-height'] = trim($_POST['p75-video-height']);

	if ( $_POST['p75-remove-video'] ) {
		// Remove video
		delete_post_meta($postID, '_videoembed');
		delete_post_meta($postID, '_videowidth');
		delete_post_meta($postID, '_videoheight');
		delete_post_meta($postID, '_videoembed_manual');
	} elseif ( $_POST['p75-video-embed'] ) {
		// Save video embed code.
		if( !update_post_meta($postID, '_videoembed_manual', $_POST['p75-video-embed'] ) )
		add_post_meta($postID, '_videoembed_manual', $_POST['p75-video-embed'] );
		delete_post_meta($postID, '_videoembed');
		delete_post_meta($postID, '_videowidth');
		delete_post_meta($postID, '_videoheight');
	} elseif ( $_POST['p75-video-url'] ) {
		// Save video URL.
		if( !update_post_meta($postID, '_videoembed', $_POST['p75-video-url'] ) )
		add_post_meta($postID, '_videoembed', $_POST['p75-video-url'] );
		delete_post_meta($postID, '_videoembed_manual');
		
		// Save width and height.
		$videoWidth = is_numeric($_POST['p75-video-width']) ? $_POST['p75-video-width'] : "";
		if( !update_post_meta($postID, '_videowidth', $videoWidth) )
		add_post_meta($postID, '_videowidth', $videoWidth);
   
		$videoHeight = is_numeric($_POST['p75-video-height']) ? $_POST['p75-video-height'] : "";
		if( !update_post_meta($postID, '_videoheight', $videoHeight) )
		add_post_meta($postID, '_videoheight', $videoHeight);
	}

}

/**
 * Gets the embed code for a video.
 *
 * @param $postID The post ID of the video
 * @return The embed code
 */
function p75GetVideo($postID) {
	if ( $videoURL = get_post_meta($postID, 'videoembed', true) ) return $videoURL;
	if ( $videoEmbed = get_post_meta($postID, '_videoembed_manual', true ) ) return $videoEmbed;

	$videoURL = get_post_meta($postID, '_videoembed', true);
	$videoWidth = get_post_meta($postID, '_videowidth', true);
	$videoHeight = get_post_meta($postID, '_videoheight', true);

	$videoEmbedder = new p75VideoEmbedder($videoURL);
	$videoEmbedder->setDefaultWidth(P75_VIDEO_W);
	$videoEmbedder->setWidth($videoWidth);
	$videoEmbedder->setHeight($videoHeight);

	return $videoEmbedder->getEmbedCode();
}

?>
