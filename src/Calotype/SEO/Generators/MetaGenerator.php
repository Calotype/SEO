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
     * The maximal length of the meta description.
     *
     * @var integer
     */
    protected $max_description_length = 160;

    /**
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

        $html[] = "<title>$title</title>";
        
        if (! empty($description)) {
            $html[] = "<meta name='description' itemprop='description' content='$description' />";
        }

        if (! empty($keywords)) {
            $html[] = "<meta name='keywords' content='{$keywords}' />";
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
    }

    /**
     * Set the Meta title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $title = strip_tags($title);

        $this->title = $title . $this->getDefault('separator') . $this->getDefault('title');
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
     * @param string $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Get the Meta title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title ?: $this->getDefault('title');
    }

    /**
     * Get the Meta keywords.
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords ?: $this->getDefault('keywords');
    }

    /**
     * Get the Meta description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description ?: $this->getDefault('description');
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
