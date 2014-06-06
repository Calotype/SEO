<?php namespace Calotype\SEO\Generators;

use Calotype\SEO\Contracts\TwitterCardAware;

class TwitterCardGenerator
{
    /**
     * The prefix used by the twitter card protocol.
     *
     * @const TWITTER_PREFIX
     */
    const TWITTER_PREFIX = 'twitter:';

    /**
     * The tag used by the twitter card protocol.
     *
     * @const META_TAG
     */
    const META_TAG = '<meta name="[property]" content="[value]" />';

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
        'card'
    );
    
    /**
     * The properties that are required for the each twitter card.
     *
     * @var array
     */
    protected $required_cards = array(
        'summary' => array(
            'title', 'description'
        ),
        'summary_large_image' => array(
            'title', 'description'
        ),
        'photo' => array(
            'image'
        ),
        'gallery' => array(
            'image0:src', 'image1:src', 'twitter:image2:src', 'twitter:image3:src'
        ),
        'app' => array(
            'app:id:iphone', 'app:id:ipad', 'twitter:app:id:googleplay'
        ),
        'player' => array(
            'title', 'description', 'player', 'player:width', 'player:height', 'image'
        ),
        'product' => array(
            'title', 'description', 'description', 'image', 'data1', 'label1', 'data2', 'label2'
        )
    );

    /**
     * Render the twitter card tags.
     *
     * @return string
     */
    public function generate()
    {
        $html = array();

        foreach ($this->properties as $property => $value) {
            $html[] = strtr(static::META_TAG, array(
                '[property]' => static::TWITTER_PREFIX . $property,
                '[value]' => $value
            ));
        }

        return implode(PHP_EOL, $html);
    }

    /**
     * Set the twitter card properties from a raw array.
     *
     * @param array $properties
     */
    public function fromRaw($properties)
    {
        $this->validateProperties($properties);

        foreach ($properties as $property => $value) {
            $this->properties[$property] = $value;
        }
    }

    /**
     * Use the twitter card data of a twitter card aware object.
     *
     * @param TwitterCardAware $object
     */
    public function fromObject(TwitterCardAware $object)
    {
        $properties = $object->getTwitterCardData();

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
    public function reset()
    {
        $this->properties = array();
    }

    /**
     * Validate to make sure the properties contain all required ones.
     *
     * @param array $properties
     */
    protected function validateProperties($properties)
    {
        foreach ($this->required as $required) {
            if (! array_key_exists($required, $properties)) {
                throw new \InvalidArgumentException("Required twitter card property [$required] is not present.");
            }
        }
        
        foreach ($this->required_cards[$properties['card']] as $required) {
            if (! array_key_exists($required, $properties)) {
                throw new \InvalidArgumentException("Required twitter card property [$required] is not present.");
            }
        }
    }
}
