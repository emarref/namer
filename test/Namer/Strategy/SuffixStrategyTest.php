<?php

namespace Emarref\Namer\Strategy;

class SuffixStrategyTest extends \PHPUnit_Framework_TestCase
{
    const STRATEGY_DEFAULT_SUFFIX    = 'copy';
    const STRATEGY_DEFAULT_INCREMENT = true;

    /**
     * @var StrategyInterface
     */
    private $strategy;

    public function setup()
    {
        $this->strategy = new SuffixStrategy(
            self::STRATEGY_DEFAULT_SUFFIX,
            self::STRATEGY_DEFAULT_INCREMENT
        );
    }

    public function testGetName()
    {
        $this->assertSame(
            $this->strategy->getName('Strategy', 0),
            'Strategy',
            'No change to first iteration'
        );

        $this->assertSame(
            $this->strategy->getName('Strategy', 1),
            sprintf('Strategy %s', self::STRATEGY_DEFAULT_SUFFIX),
            'Suffix appended on second iteration'
        );

        $this->assertSame(
            $this->strategy->getName('Strategy', 2),
            sprintf('Strategy %s 2', self::STRATEGY_DEFAULT_SUFFIX),
            'Suffix appended and incremented on third iteration'
        );
    }

    public function testGetNameCustomSuffix()
    {
        $this->strategy->setSuffix('test');

        $this->assertSame(
            $this->strategy->getName('Strategy', 1),
            'Strategy test',
            'Custom suffix appended on second iteration'
        );
    }

    public function testGetNameNotIncremental()
    {
        $this->strategy->setIncremental(false);

        $this->assertSame(
            $this->strategy->getName('Strategy', 2),
            'Strategy copy copy',
            'Non-incremental suffix appended on third iteration'
        );
    }

    public function tearDown()
    {
        unset($this->strategy);
    }
}
