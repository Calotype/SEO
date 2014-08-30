<?php namespace Calotype\SEO\Generators;

use Calotype\SEO\Contracts\MetaAware;

class MetaGenerator
{
    /**
     * The meta title.
     *
     * @var string
     */
    protected $title;

    /**
     * The meta description.
     *
     * @var string
     */
    protected $description;

    /**
     * The meta keywords.
     *
     * @var string
     */
    protected $keywords;

    /**
     * The format used to generate the meta title.
     *
     * @var string
     */
    protected $format;

    /**
     * The maximal length of the meta description.
     *
     * @var integer
     */
    protected $max_description_length = 160;

    /*
     * The default configurations.
     *
     * @var array
     */
    protected $defaults = array(
        'title' => false,
        'description' => false,
        'separator' => ' | ',
        'keywords' => false
    );

    /**
     * The canonical url.
     *
     * @var array
     */
    protected $canonical;

    /**
     * Create a new MetaGenerator instance.
     *
     * @param array $defaults
     */
    public function __construct(array $defaults = array())
    {
        foreach ($defaults as $key => $value) {
            $this->defaults[$key] = $value;
        }
    }

    /**
     * Render the meta tags.
     *
     * @return string
     */
    public function generate()
    {
        $title = $this->getTitle();
        $description = $this->getDescription();
        $keywords = $this->getKeywords();
        $canonical = $this->getCanonical();

        $html[] = "<title>$title</title>";

        if (! empty($description)) {
            $html[] = "<meta name='description' content='$description' />";
        }

        if (! empty($keywords)) {
            $html[] = "<meta name='keywords' content='$keywords' />";
        }

        if (! empty($canonical)) {
            $html[] = "<link rel='canonical' href='$canonical' />";
        }

        return implode(PHP_EOL, $html);
    }

    /**
     * Use the meta data of a MetaAware object.
     *
     * @param MetaAware $object
     */
    public function fromObject(MetaAware $object)
    {
        $data = $object->getMetaData();

        if (array_key_exists('title', $data)) {
            $this->setTitle($data['title']);
        }

        if (array_key_exists('description', $data)) {
            $this->setDescription($data['description']);
        }

        if (array_key_exists('keywords', $data)) {
            $this->setKeywords($data['keywords']);
        }

        if (array_key_exists('canonical', $data)) {
            $this->setCanonical($data['canonical']);
        }
    }

    /**
     * Set the Meta title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $title = strip_tags($title);

        $this->title = str_replace('{title}', $title, $this->getFormat());
    }

    /**
     * Set the Meta description.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $description = strip_tags($description);

        if (strlen($description) > $this->max_description_length) {
            $description = substr($description, 0, $this->max_description_length);
        }

        $this->description = $description;
    }

    /**
     * Set the Meta keywords.
     *
     * @param array|string $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = implode(',', (array) $keywords);
    }

    /**
     * Get the Meta title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title ? : $this->getDefault('title');
    }

    /**
     * Get the Meta keywords.
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords ? : $this->getDefault('keywords');
    }

    /**
     * Get the Meta description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description ? : $this->getDefault('description');
    }

    /**
     * Set the canonical url.
     *
     * @param string $url
     */
    public function setCanonical($url)
    {
        $this->canonical = $url;
    }

    /**
     * Get the canonical url.
     *
     * @return string
     */
    public function getCanonical()
    {
        return $this->canonical;
    }

    /**
     * Get the format used to generate the meta title.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set the format used to generate the meta title.
     *
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * Reset the title and description fields.
     *
     * @return void
     */
    public function reset()
    {
        $this->title = null;
        $this->description = null;
        $this->keywords = null;
        $this->canonical = null;
    }

    /**
     * Get a default configuration.
     *
     * @param string $default
     *
     * @return mixed
     */
    public function getDefault($default)
    {
        if (array_key_exists($default, $this->defaults)) {
            return $this->defaults[$default];
        }

        $class = get_class($this);
        throw new \InvalidArgumentException("{$class}: default configuration $default does not exist.");
    }
}
