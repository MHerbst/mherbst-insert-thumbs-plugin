<?php

class MHerbstInsertThumbPlugin extends KokenPlugin {

    function __construct()
    {
        $this->register_shortcode('mherbst_thumbs', 'render');
    }

    function render($attributes)
    {
    	$query = $_SERVER['QUERY_STRING'];
 /*   	$handle = fopen("/test.log","a");
    	fwrite($handle, ">>".$query."<<\n");
    	fwrite($handle, "StriPos:".stripos($query, "/text/slug:")."--".stripos($query, "/type:essay/")."\n");
    	fwrite($handle, "Stripos2:".stripos($query, "/type:page/"));
  */  	

    	if ((stripos($query, "/text/slug:") === false && !$this->data->show_in_index) && stripos($query, "/type:page/") === false)
			return "";
    	 
    	$class = $attributes['class'];
    	$style="";
        $margin = false;
        $size = "";
        if (is_numeric($attributes['margin']) && !empty($attributes['margin'])) {
            $margin = $attributes['margin'];
        }
    	if ($attributes['floating'] == "l")
    	{
    		$style = 'style="float:left;';
                if ($margin) {
                    $style .= ' margin-right: ' . $margin . 'px;';
                }
                $style .= '"'; // close style attribute
    	}
    	else if ($attributes['floating'] == "r")
    	{
    		$style = 'style="float:right;';
                if ($margin) {
                    $style .= ' margin-left: ' . $margin . 'px;';
                }
                $style .= '"'; // close style attribute
    	}

		if (!empty($attributes['size'])) {
			$size = ' size="' . $attributes['size'] . '"';
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
        <figure class="k-content">
        		{$linkbegin}<koken:img {$size} {$preset} {$width} {$lazy} />{$linkend}
        		{$caption}
      	</figure>
    </koken:load>
</div>
HTML;
    }

}


