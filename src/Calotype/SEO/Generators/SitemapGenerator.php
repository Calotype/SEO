<?php namespace Calotype\SEO\Generators;

use XMLWriter, DateTime, Traversable;
use Calotype\SEO\Contracts\SitemapAware;

class SitemapGenerator
{
    /**
     * All the elements of the sitemap.
     *
     * @var array
     */
    protected $elements = array();

    /**
     * Closures used for lazy loading.
     *
     * @var array
     */
    protected $closures = array();

    /**
     * Add a SitemapAware element to the sitemap.
     *
     * @param mixed $element
     */
    public function add($element)
    {
        if (is_a($element, 'Closure')) {
            return $this->closures[] = $element;
        }

        $location = $element->getSitemapLocation();
        $last_modified = $element->getSitemapLastModified();
        $change_frequency = $element->getSitemapChangeFrequency();
        $priority = $element->getSitemapPriority();

        if ($last_modified instanceof DateTime) {
            $last_modified = $last_modified->format('Y-m-d');
        }

        $this->elements[] = array($location, $last_modified, $change_frequency, $priority);
    }

    /**
     * Add multiple SitemapAware elements to the sitemap.
     *
     * @param mixed $elements
     */
    public function addAll($elements)
    {
        if (is_a($elements, 'Closure')) {
            return $this->closures[] = $elements;
        }

        foreach ($elements as $instance) {
            $this->add($instance);
        }
    }

    /**
     * Add a raw element to the sitemap.
     *
     * @param string $location
     * @param string $last_modified
     * @param string $change_frequency
     * @param string $priority
     */
    public function addRaw($location, $last_modified, $change_frequency = 'monthly', $priority = '0.5')
    {
        $this->elements[] = array($location, $last_modified, $change_frequency, $priority);
    }

    /**
     * Generate the xml for the sitemap.
     *
     * @return string
     */
    public function generate()
    {
        $this->loadClosures();

        $xml = new XMLWriter();
        $xml->openMemory();

        $xml->writeRaw('<?xml version="1.0" encoding="UTF-8"?>');
        $xml->writeRaw('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');

        foreach ($this->elements as $element) {
            $xml->startElement('url');
            $xml->writeElement('loc', $element[0]);
            $xml->writeElement('lastmod', $element[1]);
            $xml->writeElement('changefreq', $element[2]);
            $xml->writeElement('priority', $element[3]);
            $xml->endElement();
        }

        $xml->writeRaw('</urlset>');

        return $xml->outputMemory();
    }

    /**
     * Load the lazy loaded elements.
     *
     * @return void
     */
    protected function loadClosures()
    {
        foreach ($this->closures as $closure) {
            $instance = $closure();

            if (is_array($instance) || $instance instanceof Traversable) {
                $this->addAll($instance);
            } else {
                $this->add($instance);
            }
        }
    }
}
