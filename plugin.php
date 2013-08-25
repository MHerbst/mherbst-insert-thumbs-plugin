<?php

class MHerbstInsertThumbPlugin extends KokenPlugin {

    function __construct()
    {
        $this->register_shortcode('mhthumbs', 'render');
    }

    function render($attributes)
    {
        return <<<HTML
<div class="k-content-embed" style="float:right">
    <koken:load source="content" filter:id="{$attributes['id']}">
        <div class="k-content">
            <koken:img preset="small" />
        </div>
    </koken:load>
</div>
HTML;
    }

}
