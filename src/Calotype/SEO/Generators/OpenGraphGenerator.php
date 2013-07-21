<?php namespace Calotype\SEO\Generators;

use Calotype\SEO\Contracts\OpenGraphAware;

class OpenGraphGenerator
{
    /**
     * The prefix used by the open graph protocol.
     *
     * @const OPENGRAPH_PREFIX
     */
    const OPENGRAPH_PREFIX = 'og:';

    /**
     * The tag used by the open graph protocol.
     *
     * @const OPENGRAPH_TAG
     */
    const OPENGRAPH_TAG = '<meta property="[property]" content="[value]" />';

    /**
     * The properties that we are going to generate.
     *
     * @var array
     */
    protected $properties = array();

    /**
     * The properties that are required.
     *
     * @var array
     */
    protected $required = array(
        'title', 'type', 'image', 'url', 'site_name'
    );

    /**
     * The properties that are optional.
     *
     * @var array
     */
    protected $optional = array(
        'audio', 'description', 'determiner', 'locale', 'locale:alternate', 'video'
    );

    /**
     * Render the open graph tags.
     *
     * @return string
     */
    public function generate()
    {
        $html = array();

        foreach ($this->properties as $property => $value) {
            $html[] = strtr(static::OPENGRAPH_TAG, array(
                '[property]' => static::OPENGRAPH_PREFIX . $property,
                '[value]' => $value
            ));
        }

        return implode(PHP_EOL, $html);
    }

    /**
     * Set the open graph properties from a raw array.
     *
     * @param  array $properties
     */
    public function raw($properties)
    {
        $this->validateProperties($properties);

        foreach ($properties as $property => $value) {
            $this->properties[$property] = $value;
        }
    }

    /**
     * Use the open graph data of a open graph aware object.
     *
     * @param  OpenGraphAware $object
     */
    public function object(OpenGraphAware $object)
    {
        $properties = $object->getOpenGraphData();

        $this->validateProperties($properties);

        foreach ($properties as $property => $value) {
            $this->properties[$property] = $value;
        }
    }

    /**
     * Reset all the properties.
     *
     * @return void
     */
    public function clean()
    {
        $this->properties = array();
    }

    /**
     * Get all the properties.
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Validate to make sure the properties contain all required ones.
     *
     * @param  array $properties
     */
    protected function validateProperties($properties)
    {
        foreach ($this->required as $required) {
            if (! array_key_exists($required, $properties)) {
                throw new \InvalidArgumentException("Required open graph property [$required] is not present.");
            }
        }
    }
}
