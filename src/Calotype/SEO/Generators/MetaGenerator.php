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
     * The default configurations.
     *
     * @var array
     */
    protected $defaults = array(
        'title' => false,
        'description' => false,
        'separator' => ' | '
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

        $html[] = "<title>$title</title>";
        $html[] = "<meta name='description' content='$description' />";

        return implode(PHP_EOL, $html);
    }

    /**
     * Use the meta data of a MetaAware object.
     *
     * @param  MetaAware $object
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

        if (strlen($description) > 160) {
            $description = substr($description, 0, 160);
        }

        $this->description = $description;
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
    }

    /**
     * Get a default configuration.
     *
     * @param  string $default
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
