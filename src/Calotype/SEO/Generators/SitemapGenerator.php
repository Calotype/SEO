<?php namespace Calotype\SEO\Generators;

use XMLWriter, Traversable;
use Calotype\SEO\Contracts\SitemapAware;

class SitemapGenerator
{
    /**
     * All the entries of the sitemap.
     *
     * @var array
     */
    protected $entries = array();

    /**
     * Closures used for lazy loading.
     *
     * @var array
     */
    protected $closures = array();

    /**
     * The required fields of a sitemap entry.
     *
     * @var array
     */
    protected $required = array(
        'loc', 'lastmod', 'changefreq', 'priority'
    );

    /**
     * The attributes that should be replaced with
     * their valid counterpart for readability.
     *
     * @var array
     */
    protected $replacements = array(
        'location' => 'loc',
        'last_modified' => 'lastmod',
        'change_frequency' => 'changefreq'
    );

    /**
     * The allowed values for the change frequency.
     *
     * @var array
     */
    protected $frequencies = array(
        'always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'
    );

    /**
     * Add a SitemapAware object to the sitemap.
     *
     * @param mixed $object
     */
    public function add($object)
    {
        if (is_a($object, 'Closure')) {
            return $this->closures[] = $object;
        }

        $this->validateObject($object);

        $data = $object->getSitemapData();
        $this->validateData($data);

        $this->entries[] = $data;
    }

    /**
     * Add multiple SitemapAware objects to the sitemap.
     *
     * @param array|Traversable $objects
     */
    public function addAll($objects)
    {
        if (is_a($objects, 'Closure')) {
            return $this->closures[] = $objects;
        }

        foreach ($objects as $object) {
            $this->add($object);
        }
    }

    /**
     * Add a raw entry to the sitemap.
     *
     * @param array $data
     */
    public function addRaw($data)
    {
        $this->validateData($data);

        $this->entries[] = $data;
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

        foreach ($this->entries as $data) {
            $xml->startElement('url');

            $data = $this->replaceAttributes($data);

            foreach ($data as $attribute => $value) {
                $xml->writeElement($attribute, $value);
            }

            $xml->endElement();
        }

        $xml->writeRaw('</urlset>');

        return $xml->outputMemory();
    }

    /**
     * Validate the data for a sitemap entry.
     *
     * @param  array $data
     */
    protected function validateData($data)
    {
        $data = $this->replaceAttributes($data);

        foreach ($this->required as $requirement) {
            if (! array_key_exists($requirement, $data)) {
                $replacement = array_search($requirement, $this->replacements);

                if ($replacement !== false) {
                    $requirement = $replacement;
                }

                throw new \InvalidArgumentException("$requirement is required in the sitemap data.");
            }
        }
    }

    /**
     * Validate an element.
     *
     * @param  mixed $element
     */
    protected function validateObject($element)
    {
        if (! $element instanceof SitemapAware) {
            throw new \InvalidArgumentException("Element should implement Calotype\SEO\Contracts\SitemapAware");
        }
    }

    /**
     * Replace the attribute names with their replacements.
     *
     * @param  array $data
     *
     * @return array
     */
    protected function replaceAttributes($data)
    {
        foreach ($data as $attribute => $value) {
            $replacement = $this->replaceAttribute($attribute);
            unset($data[$attribute]);
            $data[$replacement] = $value;
        }

        return $data;
    }

    /**
     * Replace an attribute with it's replacement if available.
     *
     * @param  string $attribute
     *
     * @return string
     */
    protected function replaceAttribute($attribute)
    {
        if (array_key_exists($attribute, $this->replacements)) {
            return $this->replacements[$attribute];
        }

        return $attribute;
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
