<?php namespace Calotype\SEO\Contracts;

interface OpenGraphAware
{
    /**
     * Get the data for the Open Graph fields.
     * For more information see: http://ogp.me/
     *
     * <code>
     * $data = array(
     *     'title' => 'The Rock (1996)',
     *     'type' => 'video.movie',
     *     'image' => 'http://ia.media-imdb.com/images/M/MV5BMTM3MTczOTM1OF5BMl5BanBnXkFtZTYwMjc1NDA5._V1_SY317_CR4,0,214,317_.jpg',
     *     'url' => 'http://www.imdb.com/title/tt0117500/',
     *     'site_name' => 'IMDb'
     * );
     * </code>
     *
     * @return array
     */
    public function getOpenGraphData();
}
