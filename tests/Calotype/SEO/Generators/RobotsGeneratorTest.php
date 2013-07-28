<?php

use Calotype\SEO\Generators\RobotsGenerator;

class RobotsGeneratorTest extends PHPUnit_Framework_TestCase
{
    public function testCanGenerate()
    {
        $generator = $this->getGenerator();

        $robots_txt = $generator->generate();

        $this->assertEquals('', $robots_txt);
    }

    public function testCanAddLine()
    {
        $expected = 'foo';

        $this->assertRobotsTxt(array(
            'addLine' => 'foo'
        ), $expected);
    }

    public function testCanAddLines()
    {
        $expected = <<<ROBOTS
foo
bar
ROBOTS;

        $this->assertRobotsTxt(array(
            'addLines' => array('foo', 'bar')
        ), $expected);
    }

    public function testCanAddComment()
    {
        $expected = '# foo';

        $this->assertRobotsTxt(array(
            'addComment' => 'foo'
        ), $expected);
    }

    public function testCanAddSitemap()
    {
        $expected = 'Sitemap: example.com';

        $this->assertRobotsTxt(array(
            'addSitemap' => 'example.com'
        ), $expected);
    }

    public function testCanAddUserAgent()
    {
        $expected = 'User-agent: Googlebot';

        $this->assertRobotsTxt(array(
            'addUserAgent' => 'Googlebot'
        ), $expected);
    }

    public function testCanAddHost()
    {
        $expected = 'Host: example.com';

        $this->assertRobotsTxt(array(
            'addHost' => 'example.com'
        ), $expected);
    }

    public function testCanReset()
    {
        $expected = '';

        $this->assertRobotsTxt(array(
            'addHost' => 'example.com',
            'reset'
        ), $expected);
    }

    public function testCanAddSpacer()
    {
        $expected = <<<ROBOTS

ROBOTS;

        $this->assertRobotsTxt(array(
            'addSpacer' => ''
        ), $expected);
    }

    public function testCanAddAllow()
    {
        $expected = 'Allow: /';

        $this->assertRobotsTxt(array(
            'addAllow' => '/'
        ), $expected);
    }

    public function testCanAddMultipleAllow()
    {
        $expected = <<<ROBOTS
Allow: /
Allow: /cgi-bin
ROBOTS;

        $this->assertRobotsTxt(array(
            'addAllow' => array('/', '/cgi-bin')
        ), $expected);
    }

    public function testCanAddDisallow()
    {
        $expected = 'Disallow: /';

        $this->assertRobotsTxt(array(
            'addDisallow' => '/'
        ), $expected);
    }

    public function testCanAddMultipleDisallow()
    {
        $expected = <<<ROBOTS
Disallow: /
Disallow: /cgi-bin
ROBOTS;

        $this->assertRobotsTxt(array(
            'addDisallow' => array('/', '/cgi-bin')
        ), $expected);
    }

    protected function assertRobotsTxt(array $functions, $expected)
    {
        $generator = $this->getGenerator();

        foreach ($functions as $function => $argument) {
            if (empty($function)) {
                $generator->{$argument}();
            } else {
                $generator->{$function}($argument);
            }
        }

        $robots_txt = $generator->generate();

        $this->assertEquals($expected, $robots_txt);
    }

    protected function getGenerator()
    {
        return new RobotsGenerator();
    }
}
