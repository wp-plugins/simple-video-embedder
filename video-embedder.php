<?php
/**
 * Video URL -> video embed code class.
 *
 * Author: James Lao <lao.zephyr@gmail.com>
 * Date: February 2009
 */

class p75VideoEmbedder
{
	var $width;				// The width of the player.
	var $height;			// The height of the player.
	var $videoPlayers;	// The array of registered players.
	
	/**
	 * Uses singleton design pattern to make sure the
	 * same instance of the class is returned every time.
	 * Use p75VideoEmbedder::getInstance() to get an
	 * instance of this class.
	 */
	function getInstance()
	{
		static $instance;
		
		if ( !isset($instance) ) {
			$c = __CLASS__;
			$instance = new $c;
		}
		
		return $instance;
	}
	
	/**
	 * Sets the width of the video player.
	 *
	 * @param $width The width.
	 */
	function setWidth($width) {
		$this->width = $width;
	}
	
	/**
	 * Sets the height of the video player.
	 *
	 * @param $height The height.
	 */
	function setHeight($height) {
		$this->height = $height;
	}
	
	/**
	 * Registers a new video player. $oracle is a function
	 * that accepts a URL and returns TRUE if it is the
	 * player that should be used for the video and FALSE
	 * otherwise. $player is the function that generates the
	 * embed code from the URL. The signatures of the functions
	 * are as follows:
	 *
	 * myOracle($url)
	 * myPlayer($url, $width, $height)
	 *
	 * @param $oracle The oracle function callback.
	 * @param $player The player function callback.
	 */
	function registerPlayer($oracle, $player) {
		$this->videoPlayers[] = array( 'oracle'=>$oracle, 'player'=>$player );
	}
	
	/**
	 * Generates the proper embed code.
	 * It loops through all the registered players, asking each 
	 * oracle if its corresponding player should handle the
	 * video in question. It returns the first one
	 * that says yes.
	 *
	 * @param $url The video URL.
	 */
	function getEmbedCode($url)
	{
		foreach ( $this->videoPlayers as $id=>$player )
		{
			if ( call_user_func($player['oracle'], $url) )
				return call_user_func($player['player'], $url, $this->width, $this->height);
		}
	}
	
	function checkURL($url)
	{
		foreach ( $this->videoPlayers as $id=>$player )
		{
			if ( $player['oracle']($url) )
				return true;
		}
		
		return false;
	}
	
}

?>
