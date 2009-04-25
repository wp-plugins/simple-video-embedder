<?php
/**
 * Video URL -> video embed code class.
 *
 * Author: James Lao <lao.zephyr@gmail.com>
 * Date: February 2009
 */

class p75VideoEmbedder {

	var $url;
	var $width;
	var $height;
	var $defaultWidth;
	
	function p75VideoEmbedder($url) {
		$this->url = $url;
	}
	
	/**
	 * Sets the width of the video to use instead of the default.
	 */
	function setWidth($width) {
		$this->width = $width;
	}
	
	function setHeight($height) {
		$this->height = $height;
	}
	
	function setDefaultWidth($width) {
		$this->defaultWidth = $width;
	}
	
	/**
	 * Generates the proper embed code.
	 */
	function getEmbedCode() {
		// Watch out for flv and mp4's
		if( preg_match("/(\.flv|\.mp4)$/i", $this->url) ) {
			return $this->getJWPlayer();
		}
		
		switch( $this->getDomain() ) {
			case "youtube":
				return $this->getYouTube();
				
			case 'vimeo':
				return $this->getVimeo();
				
			case 'metacafe':
				return $this->getMetacafe();
				
			case 'seesmic':
				return $this->getSeesmic();
				
			case 'video.google':
				return $this->getGoogleVideo();
			
			case 'revver':
				return $this->getRevver();
				
			default:
				return false;
		}
	}
	
	/**
	 * Determine the domain name of the video.
	 */
	function getDomain() {
		$matches;
		preg_match("#^http://(?:www\.)?([\.a-z0-9]+)\.(?:com|tv|net)#i", $this->url, $matches);
		return $matches[1];
	}
	
	function calcWH($playerW, $playerH) {
		if( $this->width && $this->height )
			return array($this->width, $this->height);
		else if( $this->width )
			return array($this->width, ($playerH/$playerW)*$this->width);
		else if( $this->height )
			return array(($playerW/$playerH)*$this->height, $this->height);
		else
			return array($this->defaultWidth, ($playerH/$playerW)*$this->defaultWidth);
	}
	
	function getJWPlayer() {
		list($width, $height) = $this->calcWH(500, 400);
		
		return '<div id="videoContainer"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</div>
	<script type="text/javascript" src="' . get_bloginfo('url') . '/wp-content/mediaplayer-viral/swfobject.js"></script>
	<script type="text/javascript">
		var s1 = new SWFObject("' . get_bloginfo('url') . '/wp-content/mediaplayer-viral/player-viral.swf","ply","'.$width.'","'.$height.'","9","#FFFFFF");
		s1.addParam("allowfullscreen","true");
		s1.addParam("allownetworking","all");
		s1.addParam("allowscriptaccess","always");
		s1.addParam("flashvars","file=' . $this->url . '");
		s1.write("videoContainer");
	</script>';
	}
	
	function getYouTube() {
		$matches = array();
		
		// example: http://www.youtube.com/watch?v=R7yfISlGLNU
		preg_match("#http://(?:www\.)?youtube\.com/watch\?v=([_\-a-z0-9]+)#i", $this->url, $matches);
		
		if( strstr($this->url, "&fmt=22") ) // Check for HD
		{
			list($width, $height) = $this->calcWH(850, 500);
			return '<object width="' . $width . '" height="' . $height . '"><param value="http://www.youtube.com/v/' . $matches[1] . '&ap=%2526fmt%3D22" name="movie" /><param value="window" name="wmode" /><param value="true" name="allowFullScreen" /><embed width="' . $width . '" height="' . $height . '" wmode="window" allowfullscreen="true" type="application/x-shockwave-flash" src="http://www.youtube.com/v/' . $matches[1] . '&ap=%2526fmt%3D22"></embed></object>';
		}
		else
		{
			list($width, $height) = $this->calcWH(425, 344);
			return '<object width="' . $width . '" height="' . $height . '"><param name="movie" value="http://www.youtube.com/v/' . $matches[1] . '&hl=en&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $matches[1] . '&hl=en&fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $width . '" height="' . $height . '"></embed></object>';
		}
	}
	
	function getVimeo() {
		$matches = array();
		
		// example: http://vimeo.com/127768
		preg_match("#http://(?:www\.)?vimeo\.com/([0-9]+)#i", $this->url, $matches);
		list($width, $height) = $this->calcWH(400, 300);
		
		return '<object width="' . $width . '" height="' . $height . '"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=' . $matches[1] . '&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" /><embed src="http://vimeo.com/moogaloop.swf?clip_id=' . $matches[1] . '&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="' . $width . '" height="' . $height . '"></embed></object>';
	}
	
	function getMetacafe() {
		$matches = array();
		
		// example: http://www.metacafe.com/watch/2467303/hair_washing_toffee/
		preg_match("#http://(?:www\.)?metacafe\.com/watch/([0-9]+)/([_a-z0-9]+)#i", $this->url, $matches);
		list($width, $height) = $this->calcWH(400, 345);
		
		return '<embed src="http://www.metacafe.com/fplayer/' . $matches[1] . '/' . $matches[2] . '.swf" width="' . $width . '" height="' . $height . '" wmode="transparent" allowFullScreen="true" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>';
	}
	
	function getRevver() {
		$matches = array();
		
		// example: http://revver.com/video/1373455/animator-vs-animation-ii-original/
		preg_match("#http://(?:www\.)?revver\.com/video/([0-9]+)#i", $this->url, $matches);
		list($width, $height) = $this->calcWH(480, 392);
		
		return '<object width="' . $width . '" height="' . $height . '" data="http://flash.revver.com/player/1.0/player.swf?mediaId=' . $matches[1] . '" type="application/x-shockwave-flash" id="revvervideoa17743d6aebf486ece24053f35e1aa23"><param name="Movie" value="http://flash.revver.com/player/1.0/player.swf?mediaId=' . $matches[1] . '"></param><param name="FlashVars" value="allowFullScreen=true"></param><param name="AllowFullScreen" value="true"></param><param name="AllowScriptAccess" value="always"></param><embed type="application/x-shockwave-flash" src="http://flash.revver.com/player/1.0/player.swf?mediaId=' . $matches[1] . '" pluginspage="http://www.macromedia.com/go/getflashplayer" allowScriptAccess="always" flashvars="allowFullScreen=true" allowfullscreen="true" height="' . $height . '" width="' . $width . '"></embed></object>';
	}
	
	function getGoogleVideo() {
		$matches = array();
		
		// example: http://video.google.com/videoplay?docid=-8111235669135653751
		preg_match("#http://(?:www\.)?video\.google\.com/videoplay\?docid=(\-[0-9]+)#i", $this->url, $matches);
		list($width, $height) = $this->calcWH(400, 362);
		
		return '<embed id="VideoPlayback" src="http://video.google.com/googleplayer.swf?docid=' . $matches[1] . '&hl=en&fs=true" style="width:400px;height:326px" allowFullScreen="true" allowScriptAccess="always" type="application/x-shockwave-flash"> </embed>';
	}
	
	/* Viddler doesn't use video IDs so there's no apparent way to create embed code...
	function getViddler() {
		$matches = array();
		
		// example: http://www.viddler.com/explore/Powermat/videos/5/
		preg_match("#http://(?:www\.)?revver\.com/video/([0-9]+)/#i", $this->url, $matches);
	
		return '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="' . $width . '" height="' . $height . '" id="viddler_45edb989"><param name="wmode" value="transparent" /><param name="movie" value="http://www.viddler.com/player/45edb989/" /><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="true" /><embed src="http://www.viddler.com/player/45edb989/" width="' . $width . '" height="' . $height . '" type="application/x-shockwave-flash" allowScriptAccess="always" allowFullScreen="true" name="viddler_45edb989" wmode="transparent"></embed></object>';
	}
	*/
	
	function getSeesmic() {
		$matches = array();
		
		// example: http://seesmic.com/threads/veyy9lwnnm
		preg_match("#http://(?:www\.)?seesmic\.com/threads/([a-z0-9]+)#i", $this->url, $matches);
		list($width, $height) = $this->calcWH(435, 355);
		
		return "<object width='435' height='355'><param name='movie' value='http://seesmic.com/embeds/wrapper.swf'/><param name='bgcolor' value='#666666'/><param name='allowFullScreen' value='true'/><param name='allowScriptAccess' value='always'/><param name='flashVars' value='video=" . $matches[1] . "&amp;version=threadedplayer'/><embed src='http://seesmic.com/embeds/wrapper.swf' type='application/x-shockwave-flash' flashVars='video=" . $matches[1] . "&amp;version=threadedplayer' allowFullScreen='true' bgcolor='#666666' allowScriptAccess='always' width='435' height='355'></embed></object>";
	}
	
}

?>