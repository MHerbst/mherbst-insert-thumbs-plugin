<?php

class MHerbstInsertThumbPlugin extends KokenPlugin {

	function __construct() {
		$this->register_shortcode('mherbst_thumbs', 'render');
	}

	function render($attributes) 	{
		$query = $_SERVER['QUERY_STRING'];
 /*   	$handle = fopen("/test.log","a");
    	fwrite($handle, ">>".$query."<<\n");
    	fwrite($handle, "StriPos:".stripos($query, "/text/slug:")."--".stripos($query, "/type:essay/")."\n");
    	fwrite($handle, "Stripos2:".stripos($query, "/type:page/"));
  */  	

		if ((stripos($query, "/text/slug:") === false && !$this->data->show_in_index) && stripos($query, "/type:page/") === false)
			return "";

		$class = $attributes['class'];
		if ($class === "") {
			$class = $this->data->default_css;
		}
			
		$style="";
		$margin = false;
		if (is_numeric($attributes['margin']) && !empty($attributes['margin'])) {
			$margin = $attributes['margin'];
		}
		if ($attributes['floating'] == "l") {
			$style = 'style="float:left;';
			if ($margin) {
				$style .= ' margin-right: ' . $margin . 'px;';
			}
			$style .= '"'; // close style attribute
		} else if ($attributes['floating'] == "r") {
			$style = 'style="float:right;';
			if ($margin) {
				$style .= ' margin-left: ' . $margin . 'px;';
			}
			$style .= '"'; // close style attribute
		}

		if (!empty($attributes['size'])) {
			$size = ' size="' . $attributes['size'] . '"';
		} 
		else {
			$size = "";
		} 

		$width = "";
		$presetVal = $attributes['preset'];
		if ($attributes['width'] != "") {
			$width = 'width="'.$attributes['width'].'"';
			$wi = intval($attributes['width']);
//			Tiny" (60px), "small" (100px), "medium" (480px), "medium_large" (800)
			if ($presetVal != "no") {
				if ($wi > 800) {
					$presetVal = "no";
				} else if ($wi > 480 && $presetVal != "ml") {
					$presetVal = "ml";
				} else if ($wi > 100 && ($presetVal == "t" || $presetVal == "s")) {
					$presetVal = "m";
				} else if ($wi > 60 && $presetVal == "t") {
					$presetVal = "s";
				}
			}
		}
		$height = ($attributes['height'] != "") ? 'height="'.$attributes['height'].'"' : "";

		$crop = "";
		$preset = "";
		if ($attributes['crop'] == "true") {		
			$crop = 'crop="true"';
		}
		if ($crop == "" && $size == "") { // ignore preset if crop is set to true or size is given		
			switch($presetVal) {
				case "t":
					$preset = 'preset="tiny"';
					break;
				case "s":
					$preset = 'preset="small"';
					break;
				case "m":
					$preset = 'preset="medium"';
					break;
				case "ml":
					$preset = 'preset="medium-large"';
					break;
			}
		}
		switch($attributes['caption'])
		{
			case "t":
				$caption = '<span class="k-content-title">{{ content.title }}</span>';
				break;
			case "c":
				$caption = '<span class="k-content-caption">{{ content.caption }}</span>';
				break;
			case "b":
				$caption = '<span class="k-content-title">{{ content.title }}</span> - <span class="k-content-caption">{{ content.caption }}</span>';
				break;
			default:
				$caption = "";
				break;
		}
		if ($caption != "")
		{
			$caption = '<figcaption class="k-content-text">'.$caption.'</figcaption>';
		}
		
		$addStyle = "";
		if ($width != "" && $size != "") { // in this case the width must be set on the surrounding tag otherwise Koken would ignore size
			$addStyle = 'style="width: '.$attributes['width'].'px"';
			$width = "";
		}
		if ($this->data->lazy_load) {
			$lazy = 'lazy="true"';
		}
		if ($preset == "" && $width == "" && $addStyle == "") {
			$lazy = "";
			$addStyle = 'style="width: 100%;"';	
		}
		switch($attributes['link'])
		{
			case "lightbox":
				$linkbegin = '<koken:link lightbox="true">';
				break;
			case "detail":
				$linkbegin = "<koken:link>";
				break;
			case "album";
				$linkbegin = '<koken:link to="album" filter:id="'.$attributes['album'].'">';
				$imgdata = 'data="content.context"';
				break;
			case "custom";
				$linkbegin = '<koken:link url="'.$attributes['custom_url'].'" target="_blank">';
				break;
			default:
				$linkbegin = "";
						
		}
		$linkend = ($linkbegin != "") ? "</koken:link>" : "";
		if ($this->data->add_link_to_caption && $caption != "" && $linkbegin != "") {
			$caption = $linkbegin.$caption.$linkend;
		} 
		return <<<HTML
		<!-- koken:img {$size} {$preset} {$width} {$height} {$lazy} {$crop} add: {$addStyle} -->
<div class="k-content {$class}" {$style}>
	<koken:load source="content" filter:id="{$attributes['id']}">
		<figure class="k-content-embed" {$addStyle}>
			{$linkbegin}<koken:img {$size} {$preset} {$width} {$height} {$lazy} {$crop} />{$linkend}
			{$caption}
		</figure>
	</koken:load>
</div>
HTML;
	}
}
