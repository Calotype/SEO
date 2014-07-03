<?php

use Calotype\SEO\Generators\TwitterCardGenerator;

class TwitterCardGeneratorTest extends PHPUnit_Framework_TestCase
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

        $this->assertEquals($this->getStub('twittercard'), $data . PHP_EOL);
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

        $this->assertEquals($this->getStub('twittercard'), $data . PHP_EOL);
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
            'card' => 'summary',
            'title' => 'Foo',
            'description' => 'Foo bar',
        );
    }

    protected function getInvalidProperties()
    {
        return array(
            'card' => 'summary',
            'title' => 'Foo',
        );
    }

    public function getObjectMock($data)
    {
        return Mockery::mock('Calotype\SEO\Contracts\TwitterCardAware', array(
            'getTwitterCardData' => $data,
        ));
    }

    protected function getStub($name)
    {
        return file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . $name);
    }

    protected function getGenerator()
    {
        return new TwitterCardGenerator();
    }
}
