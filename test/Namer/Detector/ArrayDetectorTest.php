<?php

namespace Emarref\Namer\Detector;

class ArrayDetectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @array
     */
    private static $pool = [
        'Apple',
    ];

    /**
     * @var ArrayDetector
     */
    private $detector;

    public function setup()
    {
        $this->detector = new ArrayDetector(self::$pool);
    }

    public function testIsAvailable()
    {
        $this->assertTrue($this->detector->isAvailable('Test'), 'Available names return true');
        $this->assertFalse($this->detector->isAvailable('Apple'), 'Unavailable names return false');
    }

    public function tearDown()
    {
        unset($this->detector);
    }
}
