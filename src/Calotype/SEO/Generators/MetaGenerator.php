<?php namespace Calotype\SEO\Generators;

use Config;
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
     * Use the meta data of a MetaAware instance.
     *
     * @param  MetaAware $instance
     */
    public function instance(MetaAware $instance)
    {
        $title = $instance->getMetaTitle();
        $description = $instance->getMetaDescription();

        if ($suffix = $instance->getMetaTitleSuffix()) {
            $title = $title . Config::get('doit::seo.separator') . $suffix;
        }

        $this->setTitle($title);
        $this->setDescription($description);
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
        return $this->title ?: Config::get('doit::seo.title');
    }

    /**
     * Get the Meta description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description ?: Config::get('doit::seo.description');
    }
}
