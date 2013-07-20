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

        $generator->addRaw(array(
            'location' => 'example.com',
            'last_modified' => '2013-01-28',
            'change_frequency' => 'weekly',
            'priority' => '0.65'
        ));

        $sitemap = $generator->generate();

        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/xml/sitemap.xml', $sitemap);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDataWithoutRequiredFieldsThrowsError()
    {
        $generator = $this->getGenerator();

        $generator->addRaw(array(
            'location' => 'example.com'
        ));

        $sitemap = $generator->generate();

        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/xml/sitemap.xml', $sitemap);
    }

    public function testCanAddElement()
    {
        $generator = $this->getGenerator();

        $element = $this->getElementMock(array(
            'location' => 'example.com',
            'last_modified' => '2013-01-28',
            'change_frequency' => 'weekly',
            'priority' => '0.65'
        ));

        $generator->add($element);
        $sitemap = $generator->generate();

        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/xml/sitemap.xml', $sitemap);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testElementMustImplementContract()
    {
        $generator = $this->getGenerator();

        $element = new stdClass;

        $generator->add($element);
        $sitemap = $generator->generate();

        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/xml/sitemap.xml', $sitemap);
    }

    public function testCanAddMultipleElements()
    {
        $generator = $this->getGenerator();

        $elements = array();

        $elements[] = $this->getElementMock(array(
            'location' => 'example.com',
            'last_modified' => '2013-01-28',
            'change_frequency' => 'weekly',
            'priority' => '0.65'
        ));

        $elements[] = $this->getElementMock(array(
            'location' => 'example.org',
            'last_modified' => '2013-01-30',
            'change_frequency' => 'monthly',
            'priority' => '0.5'
        ));

        $generator->addAll($elements);
        $sitemap = $generator->generate();

        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/xml/sitemap-multiple.xml', $sitemap);
    }

    public function testCanAddClosure()
    {
        $generator = $this->getGenerator();

        $me = $this;
        $generator->add(function() use ($me) {
            return $me->getElementMock(array(
                'location' => 'example.com',
                'last_modified' => '2013-01-28',
                'change_frequency' => 'weekly',
                'priority' => '0.65'
            ));
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

            $elements[] = $this->getElementMock(array(
                'location' => 'example.com',
                'last_modified' => '2013-01-28',
                'change_frequency' => 'weekly',
                'priority' => '0.65'
            ));

            $elements[] = $this->getElementMock(array(
                'location' => 'example.org',
                'last_modified' => '2013-01-30',
                'change_frequency' => 'monthly',
                'priority' => '0.5'
            ));

            return $elements;
        });

        $sitemap = $generator->generate();

        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/xml/sitemap-multiple.xml', $sitemap);
    }

    public function getElementMock($data)
    {
        return Mockery::mock('Calotype\SEO\Contracts\SitemapAware', array(
            'getSitemapData' => $data,
        ));
    }

    protected function getGenerator()
    {
        return new SitemapGenerator();
    }
}
