<?php namespace Calotype\SEO\Contracts;

interface SitemapAware
{
    /**
     * Get the data for the sitemap.
     * Required elements: location, last_modified, change_frequency, priority
     *
     * <code>
     * $data = array(
     *     'location' => 'example.org',
     *     'last_modified' => '2013-01-28',
     *     'change_frequency' => 'weekly',
     *     'priority' => '0.5'
     * );
     * </code>
     *
     * @return array
     */
    public function getSitemapData();
}
