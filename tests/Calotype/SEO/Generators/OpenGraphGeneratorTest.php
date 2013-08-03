<?php

use Calotype\SEO\Generators\OpenGraphGenerator;

class OpenGraphGeneratorTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testCanGenerate()
    {
        $generator = $this->getGenerator();

        $data = $generator->generate();

        $this->assertEquals('', $data);
    }

    public function testCanSetFromRaw()
    {
        $generator = $this->getGenerator();
        $generator->fromRaw($this->getValidProperties());

        $data = $generator->generate();

        $this->assertEquals($this->getStub('opengraph'), $data . PHP_EOL);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCantSetFromRawInvalidProperties()
    {
        $generator = $this->getGenerator();
        $generator->fromRaw($this->getInvalidProperties());

        $data = $generator->generate();
    }

    public function testCanSetFromObject()
    {
        $generator = $this->getGenerator();
        $object = $this->getObjectMock($this->getValidProperties());
        $generator->fromObject($object);

        $data = $generator->generate();

        $this->assertEquals($this->getStub('opengraph'), $data . PHP_EOL);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCantSetFromObjectWithInvalidProperties()
    {
        $generator = $this->getGenerator();
        $object = $this->getObjectMock($this->getInvalidProperties());
        $generator->fromObject($object);

        $data = $generator->generate();
    }

    public function testCanReset()
    {
        $generator = $this->getGenerator();
        $generator->fromRaw($this->getValidProperties());

        $generator->reset();
        $data = $generator->generate();

        $this->assertEquals('', $data);
    }

    protected function getValidProperties()
    {
        return array(
            'title' => 'Foo',
            'type' => 'video.movie',
            'image' => 'http://example.org/foo.jpg',
            'url' => 'http://example.org/foo',
            'site_name' => 'Example.org'
        );
    }

    protected function getInvalidProperties()
    {
        return array(
            'title' => 'Foo',
            'type' => 'video.movie'
        );
    }

    public function getObjectMock($data)
    {
        return Mockery::mock('Calotype\SEO\Contracts\OpenGraphAware', array(
            'getOpenGraphData' => $data,
        ));
    }

    protected function getStub($name)
    {
        return file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . $name);
    }

    protected function getGenerator()
    {
        return new OpenGraphGenerator();
    }
}
