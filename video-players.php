<?php
	
	function vimeoOracle($url)
	{
		return (bool) preg_match("#http://(?:www\.)?vimeo\.com/([0-9]+)#i", $url);
	}

	function vimeoPlayer($url, $width, $height)
	{
		$matches = array();
		
		// example: http://vimeo.com/127768
		preg_match("#http://(?:www\.)?vimeo\.com/([0-9]+)#i", $url, $matches);
		
		return '<object width="' . $width . '" height="' . $height . '"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=' . $matches[1] . '&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" /><embed src="http://vimeo.com/moogaloop.swf?clip_id=' . $matches[1] . '&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="' . $width . '" height="' . $height . '"></embed></object>';
	}

	function youtubeOracle($url)
	{
		return (bool) preg_match("#http://(?:www\.)?youtube\.com/watch\?v=([_\-a-z0-9]+)#i", $url);
	}

	function youtubePlayer($url, $width, $height)
	{
		$matches = array();
		
		// example: http://www.youtube.com/watch?v=R7yfISlGLNU
		preg_match("#http://(?:www\.)?youtube\.com/watch\?v=([_\-a-z0-9]+)#i", $url, $matches);
		
		if( strstr($url, "&fmt=22") ) // Check for HD
		{
			$res = '<object width="' . $width . '" height="' . $height . '"><param value="http://www.youtube.com/v/' . $matches[1] . '&ap=%2526fmt%3D22';
			if ( strstr($url, "&autoplay=1") ) $res .= '&autoplay=1';
			$res .= '" name="movie" /><param value="window" name="wmode" /><param value="true" name="allowFullScreen" /><embed width="' . $width . '" height="' . $height . '" wmode="window" allowfullscreen="true" type="application/x-shockwave-flash" src="http://www.youtube.com/v/' . $matches[1] . '&ap=%2526fmt%3D22';
			if ( strstr($url, "&autoplay=1") ) $res .= '&autoplay=1';
			$res .= '"></embed></object>';
			return $res;
		}
		else
		{
			$res = '<object width="' . $width . '" height="' . $height . '"><param name="movie" value="http://www.youtube.com/v/' . $matches[1] . '&hl=en&fs=1';
			if ( strstr($url, "&autoplay=1") ) $res .= '&autoplay=1';
			$res .= '"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $matches[1] . '&hl=en&fs=1';
			if ( strstr($url, "&autoplay=1") ) $res .= '&autoplay=1';
			$res .= '" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $width . '" height="' . $height . '"></embed></object>';
			return $res;
		}
	}
	
	function metacafeOracle($url)
	{
		return (bool) preg_match("#http://(?:www\.)?metacafe\.com/watch/([_\-a-z0-9]+)/([_\-a-z0-9]+)#i", $url);
	}
	
	function metacafePlayer($url, $width, $height)
	{
		$matches = array();
		
		// example: http://www.metacafe.com/watch/2467303/hair_washing_toffee/
		preg_match("#http://(?:www\.)?metacafe\.com/watch/([_\-a-z0-9]+)/([_\-a-z0-9]+)#i", $url, $matches);
		
		return '<embed src="http://www.metacafe.com/fplayer/' . $matches[1] . '/' . $matches[2] . '.swf" width="' . $width . '" height="' . $height . '" wmode="transparent" allowFullScreen="true" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>';
	}
	
	function revverOracle($url)
	{
		return preg_match("#http://(?:www\.)?revver\.com/video/([0-9]+)#i", $url);
	}
	
	function revverPlayer($url, $width, $height)
	{
		$matches = array();
		
		// example: http://revver.com/video/1373455/animator-vs-animation-ii-original/
		preg_match("#http://(?:www\.)?revver\.com/video/([0-9]+)#i", $url, $matches);
		
		return '<object width="' . $width . '" height="' . $height . '" data="http://flash.revver.com/player/1.0/player.swf?mediaId=' . $matches[1] . '" type="application/x-shockwave-flash" id="revvervideoa17743d6aebf486ece24053f35e1aa23"><param name="Movie" value="http://flash.revver.com/player/1.0/player.swf?mediaId=' . $matches[1] . '"></param><param name="FlashVars" value="allowFullScreen=true"></param><param name="AllowFullScreen" value="true"></param><param name="AllowScriptAccess" value="always"></param><embed type="application/x-shockwave-flash" src="http://flash.revver.com/player/1.0/player.swf?mediaId=' . $matches[1] . '" pluginspage="http://www.macromedia.com/go/getflashplayer" allowScriptAccess="always" flashvars="allowFullScreen=true" allowfullscreen="true" height="' . $height . '" width="' . $width . '"></embed></object>';
	}
	
	function googleVideoOracle($url)
	{
		return (bool) preg_match("#http://(?:www\.)?video\.google\.com/videoplay\?docid=([\-0-9]+)#i", $url);
	}
	
	function googleVideoPlayer($url, $width, $height)
	{
		$matches = array();
		// example: http://video.google.com/videoplay?docid=-8111235669135653751
		preg_match("#http://(?:www\.)?video\.google\.com/videoplay\?docid=([\-0-9]+)#i", $url, $matches);
		
		return '<embed id="VideoPlayback" src="http://video.google.com/googleplayer.swf?docid=' . $matches[1] . '&hl=en&fs=true" style="width:' . $width . 'px;height:' . $height . 'px" allowFullScreen="true" allowScriptAccess="always" type="application/x-shockwave-flash"></embed>';
	}
	
	function seesmicOracle($url)
	{
		return (bool) preg_match("#http://(?:www\.)?seesmic\.com/threads/([a-z0-9]+)#i", $url);
	}
	
	function seesmicPlayer($url, $width, $height) {
		$matches = array();
		
		// example: http://seesmic.com/threads/veyy9lwnnm
		preg_match("#http://(?:www\.)?seesmic\.com/threads/([a-z0-9]+)#i", $url, $matches);
		
		return "<object width='" . $width . "' height='" . $height . "'><param name='movie' value='http://seesmic.com/embeds/wrapper.swf'/><param name='bgcolor' value='#666666'/><param name='allowFullScreen' value='true'/><param name='allowScriptAccess' value='always'/><param name='flashVars' value='video=" . $matches[1] . "&amp;version=threadedplayer'/><embed src='http://seesmic.com/embeds/wrapper.swf' type='application/x-shockwave-flash' flashVars='video=" . $matches[1] . "&amp;version=threadedplayer' allowFullScreen='true' bgcolor='#666666' allowScriptAccess='always' width='" . $width . "' height='" . $height . "'></embed></object>";
	}
	
	function jwPlayerOracle($url)
	{
		return (bool) preg_match("/(\.flv|\.mp4)$/i", $url);
	}
	
	function jwPlayer($url, $width, $height) {
		$flashvars = get_option('p75_jw_flashvars');
		if ( !empty($flashvars) && substr($flashvars, 0, 1)!='&' )
			$flashvars = '&' . $flashvars;
		
		$fileLoc = get_option('p75_jw_files');
		if ( substr($fileLoc, -1)!='/' )
			$fileLoc = $fileLoc . '/';
		
		return '<div id="videoContainer"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</div>
	<script type="text/javascript" src="' . $fileLoc . 'swfobject.js"></script>
	<script type="text/javascript">
		var s1 = new SWFObject("' . $fileLoc . 'player-viral.swf","ply","'.$width.'","'.$height.'","9","#FFFFFF");
		s1.addParam("allowfullscreen","true");
		s1.addParam("allownetworking","all");
		s1.addParam("allowscriptaccess","always");
		s1.addParam("flashvars","file=' . $url . $flashvars . '");
		s1.write("videoContainer");
	</script>';
	}
	
	$videoEmbedder = p75VideoEmbedder::getInstance();
	
	// Register all the videos.
	$videoEmbedder->registerPlayer("vimeoOracle", "vimeoPlayer");
	$videoEmbedder->registerPlayer("youtubeOracle", "youtubePlayer");
	$videoEmbedder->registerPlayer("metacafeOracle", "metacafePlayer");
	$videoEmbedder->registerPlayer("revverOracle", "revverPlayer");
	$videoEmbedder->registerPlayer("googleVideoOracle", "googleVideoPlayer");
	$videoEmbedder->registerPlayer("seesmicOracle", "seesmicPlayer");
	$videoEmbedder->registerPlayer("jwPlayerOracle", "jwPlayer");

?>
