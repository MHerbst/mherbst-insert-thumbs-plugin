<?php

class MHerbstInsertThumbPlugin extends KokenPlugin {

	
    function __construct()
    {
        $this->register_shortcode('mherbst_thumbs', 'render');
    }

    function render($attributes)
    {
    	$query = $_SERVER['QUERY_STRING'];
/*    	$handle = fopen("/test.log","a");
    	fwrite($handle, ">>".$query."<<");
    	fwrite($handle, "yxxxxx".stripos($query, "categories/slug")."\n");
    	fclose($handle);
*/    	
    	// 
    	if ((stripos($query, "/type:essay/") || stripos($query, "categories/slug")) && !$this->data->show_in_index)
    		return "";
    	
    	$class = $attributes['class'];
    	$figclass = $attributes['figclass'];
    	 
    	$style="";
    	if ($attributes['floating'] == "l")
    	{
    		$style = 'style="float:left;"';
    	}
    	else if ($attributes['floating'] == "r")
    	{
    		$style = 'style="float:right;';
    	}
	

    	$width = ($attributes['width'] != "") ? 'width="'.$attributes['width'].'"' : "";
    	switch($attributes['preset']) 
    	{
    		case "t":
    			$preset = 'preset="tiny"';
    			break;
    		case "s":
    			$preset = 'preset="small"';
    			break;
    		case "m":
    			$preset = 'preset="medium"';
    			break;
    		default:
    			$preset = "";    				 
    	}
    	$lazy = 'lazy="true"';
    	if ($preset == "" && $width == "")
    		$lazy = "";
 	
		switch($attributes['caption'])
		{
			case "t":
				$caption = "{{ content.title }}";
				break;
			case "c":
				$caption = "{{ content.caption }}";
				break;
			case "b":
				$caption = "{{ content.title }} - {{ content.caption }}";
				break;
			default:
				$caption = "";
				break;
		}
		if ($caption != "")
		{
			$caption = "<figcaption>".$caption."</figcaption>";
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
				$linkbegin = '<koken:link url="'.$attributes['custom_url'].'">';
				break;
			default:
				$linkbegin = "";
						
		}
		$linkend = ($linkbegin != "") ? "</koken:link>" : "";
        return <<<HTML
<div class="k-content-embed {$class}" {$style}>
    <koken:load source="content" filter:id="{$attributes['id']}">
        <figure class="k-content {$figclass}">
        		{$linkbegin}<koken:img {$preset} {$width} {$lazy} />{$linkend}
        		{$caption}
      	</figure>
    </koken:load>
</div>
HTML;
    }

}


