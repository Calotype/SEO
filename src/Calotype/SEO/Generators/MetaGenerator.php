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

        $html[] = "<title>{$title}</title>";
        $html[] = "<meta name='description' content='{description}' />";

        return implode(PHP_EOL, $html);
    }

    /**
     * Use the meta data of a MetaAware object.
     *
     * @param  MetaAware $object
     */
    public function object(MetaAware $object)
    {
        $data = $object->getMetaData();

        if (array_key_exists('title', $data)) {
            $this->setTitle($data['title']);
        }

        if (array_key_exists('description', $data)) {
            $this->setDescription('description');
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

        $this->title = $title . Config::get('doit::seo.separator') . Config::get('doit::seo.title');
    }

    /**
     * Set the Meta description.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $description = substr(strip_tags($description), 0, 150);

        $last_dot = strrpos($description, '.');

        if ($last_dot !== false and $last_dot > 120) {
            return $this->description = substr($description, 0, $last_dot + 1);
        }

        $this->description = $description . ' ...';
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
     * Get a default configuration.
     *
     * @param  string $default
     *
     * @return mixed
     */
    protected function getDefault($default)
    {
        if (array_key_exists($default, $this->defaults)) {
            return $this->defaults[$default];
        }

        throw new \InvalidArgumentException("Default configuration $default does not exist.");
    }
}
