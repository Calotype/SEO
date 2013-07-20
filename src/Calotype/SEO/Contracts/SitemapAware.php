<?php namespace Calotype\SEO\Contracts;

interface SitemapAware
{
    public function getSitemapLocation();
    public function getSitemapLastModified();
    public function getSitemapChangeFrequency();
    public function getSitemapPriority();
}
