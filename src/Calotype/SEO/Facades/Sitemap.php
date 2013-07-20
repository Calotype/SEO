<?php namespace Calotype\SEO\Facades;

use Illuminate\Support\Facades\Facade;

class Sitemap extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'calotype.seo.generators.sitemap'; }
}
