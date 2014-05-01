<?php

use Calotype\SEO\Generators\MetaGenerator;

class MetaGeneratorTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testCanGenerate()
    {
        $generator = $this->getGenerator();

        $data = $generator->generate();

        $this->assertEquals($this->getStub('meta-empty'), $data . PHP_EOL);
    }

    public function testCanSetTitle()
    {
        $generator = $this->getGenerator();

        $generator->setTitle('Bar');
        $data = $generator->generate();

        $this->assertEquals($this->getStub('meta-with-title'), $data . PHP_EOL);
    }

    public function testCanSetDescription()
    {
        $generator = $this->getGenerator();

        $generator->setDescription('Foobar is nice!');
        $data = $generator->generate();

        $this->assertEquals($this->getStub('meta-with-description'), $data . PHP_EOL);
    }

    public function testCanSetLongDescription()
    {
        $generator = $this->getGenerator();

        $generator->setDescription('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.');
        $data = $generator->generate();

        $this->assertEquals($this->getStub('meta-with-long-description'), $data . PHP_EOL);
        $this->assertEquals(160, strlen($generator->getDescription()), 'Description is incorrectly fixed to a maximum of 160 characters.');
    }

    public function testCanSetKeywords()
    {
        $generator = $this->getGenerator();

        $generator->setKeywords(array('foo', 'bar', 'baz'));
        $data = $generator->generate();

        $this->assertEquals($this->getStub('meta-with-keywords'), $data . PHP_EOL);

        $generator->setKeywords('foo,bar,baz');
        $data = $generator->generate();

        $this->assertEquals($this->getStub('meta-with-keywords'), $data . PHP_EOL);
    }

    public function testCanSetCanonical()
    {
        $generator = $this->getGenerator();

        $generator->setCanonical('http://example.org');
        $data = $generator->generate();

        $this->assertEquals($this->getStub('meta-with-canonical'), $data . PHP_EOL);
    }

    public function testCanSetFromObject()
    {
        $generator = $this->getGenerator();
        $object = $this->getObjectMock($this->getValidProperties());
        $generator->fromObject($object);

        $data = $generator->generate();

        $this->assertEquals($this->getStub('meta-with-all'), $data . PHP_EOL);
    }

    public function testCanGetDefaultSetting()
    {
        $generator = $this->getGenerator();

        $this->assertEquals('Foo', $generator->getDefault('title'));
        $this->assertEquals('This is a description', $generator->getDefault('description'));
    }

    public function testCanResetMetas()
    {
        $generator = $this->getGenerator();

        $generator->setTitle('Bar');
        $generator->setDescription('Lorem ipsum dolor sit amet.');
        $generator->setKeywords(array('foo', 'bar', 'baz'));
        $generator->setCanonical('http://foobar.baz');
        $generator->reset();

        $this->assertEquals('Foo', $generator->getTitle());
        $this->assertEquals('This is a description', $generator->getDescription());
        $this->assertEmpty($generator->getKeywords());
        $this->assertEquals('', $generator->getCanonical());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCantGetNonExistentDefault()
    {
        $generator = $this->getGenerator();
        $generator->getDefault('foo');

        $generator->generate();
    }

    protected function getValidProperties()
    {
        return array(
            'title' => 'Bar',
            'description' => 'Foobar is nice!',
            'keywords' => array('foo', 'bar', 'baz'),
            'canonical' => 'http://example.org'
        );
    }

    public function getObjectMock($data)
    {
        return Mockery::mock('Calotype\SEO\Contracts\MetaAware', array(
            'getMetaData' => $data,
        ));
    }

    protected function getStub($name)
    {
        return file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . $name);
    }

    protected function getGenerator()
    {
        return new MetaGenerator(array(
            'title' => 'Foo',
            'description' => 'This is a description',
            'separator' => ' - '
        ));
    }
}
