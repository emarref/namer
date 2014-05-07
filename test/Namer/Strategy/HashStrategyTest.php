<?php

namespace Emarref\Namer\Strategy;

class HashStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HashStrategy
     */
    private $strategy;

    public function setup()
    {
        $this->strategy = new HashStrategy();
    }

    public function testGetName()
    {
        $this->assertSame(sha1('Name' . 1), $this->strategy->getName('Name', 1), 'Hash strategy returns hashed value.');
    }

    public function testSetValidStrategy()
    {
        $this->strategy->setHashingStrategy(HashStrategy::HASHING_STRATEGY_SHA1);
        $this->strategy->setHashingStrategy(HashStrategy::HASHING_STRATEGY_MD5);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetInvalidStrategy()
    {
        $this->strategy->setHashingStrategy('Unknown');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Could not determine callback for strategy "unknown"
     */
    public function testGetInvalidHashingStrategyCallback()
    {
        $property = new \ReflectionProperty('Emarref\Namer\Strategy\HashStrategy', 'hashingStrategy');
        $property->setAccessible(true);
        $property->setValue($this->strategy, 'unknown');

        $method = new \ReflectionMethod('Emarref\Namer\Strategy\HashStrategy', 'getHashingStrategyCallback');
        $method->setAccessible(true);
        $method->invoke($this->strategy);
    }

    public function tearDown()
    {
        unset($this->strategy);
    }
}
