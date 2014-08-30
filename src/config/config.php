<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | These are the default values use to generate the meta tags of a page.
    |--------------------------------------------------------------------------
    */

    'defaults' => array(
        'title' => false,
        'description' => false,
    ),

    /*
    |--------------------------------------------------------------------------
    | Here you can set a default format for the title tag
    | you can use the placeholder {title}.
    |--------------------------------------------------------------------------
    */

    'title_format' => '{title} - Application',

    /*
    |--------------------------------------------------------------------------
    | Should we generate the /sitemap.xml and /robots.txt routes
    | with sensible defaults? Set this to false if you are
    | going to provide your own routes to handle them.
    |--------------------------------------------------------------------------
    */

    'generate_routes' => true

);
