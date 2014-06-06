<?php namespace Calotype\SEO\Contracts;

interface TwitterCardAware
{
    /**
     * Get the data for the Twitter Card fields.
     * For more information see: https://dev.twitter.com/docs/cards/getting-started
     *
     * <code>
     * $data = array(
     *     'card' => 'summary',
     *     'title' => 'The Rock (1996)',
     *     'description' => 'A mild-mannered chemist and an ex-con must lead the counterstrike'
     * );
     * </code>
     *
     * @return array
     */
    public function getTwitterCardData();
}
