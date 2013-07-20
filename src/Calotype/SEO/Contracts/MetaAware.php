<?php namespace Calotype\SEO\Contracts;

interface MetaAware
{
    /**
     * Get the data for the meta fields.
     *
     * <code>
     * $data = array(
     *     'title' => 'About us',
     *     'description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.'
     * );
     * </code>
     *
     * @return array
     */
    public function getMetaData();
}
