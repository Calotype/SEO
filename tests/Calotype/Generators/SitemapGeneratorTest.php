<?php

use Calotype\SEO\Generators\SitemapGenerator;

class SitemapGeneratorTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testCanGenerate()
    {
        $generator = $this->getGenerator();

        $sitemap = $generator->generate();

        $this->assertContains('xml', $sitemap);
        $this->assertContains('urlset', $sitemap);
    }

    public function testCanAddRawElement()
    {
        $generator = $this->getGenerator();

        $generator->addRaw('example.com', '2013-01-28', 'weekly', '0.65');
        $sitemap = $generator->generate();

        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/xml/sitemap.xml', $sitemap);
    }

    public function testCanAddElement()
    {
        $generator = $this->getGenerator();

        $element = $this->getElementMock('example.com', '2013-01-28', 'weekly', '0.65');
        $generator->add($element);
        $sitemap = $generator->generate();

        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/xml/sitemap.xml', $sitemap);
    }

    public function testCanAddMultipleElements()
    {
        $generator = $this->getGenerator();

        $elements = array();
        $elements[] = $this->getElementMock('example.com', '2013-01-28', 'weekly', '0.65');
        $elements[] = $this->getElementMock('example.org', '2013-01-30', 'monthly', '0.5');
        $generator->addAll($elements);
        $sitemap = $generator->generate();

        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/xml/sitemap-multiple.xml', $sitemap);
    }

    public function testCanAddClosure()
    {
        $generator = $this->getGenerator();

        $me = $this;
        $generator->add(function() use ($me) {
            return $me->getElementMock('example.com', '2013-01-28', 'weekly', '0.65');
        });
        $sitemap = $generator->generate();

        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/xml/sitemap.xml', $sitemap);
    }

    public function testCanAddClosureWithMultipleElements()
    {
        $generator = $this->getGenerator();

        $me = $this;
        $generator->addAll(function() use ($me) {
            $elements = array();
            $elements[] = $me->getElementMock('example.com', '2013-01-28', 'weekly', '0.65');
            $elements[] = $me->getElementMock('example.org', '2013-01-30', 'monthly', '0.5');
            return $elements;
        });
        $sitemap = $generator->generate();

        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/xml/sitemap-multiple.xml', $sitemap);
    }

    public function getElementMock($location, $last_modification, $change_frequency, $priority)
    {
        return Mockery::mock('Calotype\SEO\Contracts\SitemapAware', array(
            'getSitemapLocation' => $location,
            'getSitemapLastModified' => $last_modification,
            'getSitemapChangeFrequency' => $change_frequency,
            'getSitemapPriority' => $priority
        ));
    }

    protected function getGenerator()
    {
        return new SitemapGenerator();
    }
}
