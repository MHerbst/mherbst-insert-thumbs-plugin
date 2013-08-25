<?php

class MHerbstInsertThumbPlugin extends KokenPlugin {

    function __construct()
    {
        $this->register_shortcode('mherbst_insertthumb', 'render');
    }

    function render($attributes)
    {
        return <<<HTML
<div class="k-content-embed">
    <koken:load source="content" filter:id="{$attributes['id']}">
        <div class="k-content">
            <koken:img />
        </div>
    </koken:load>
</div>
HTML;
    }

}
