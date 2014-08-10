<?php

namespace Emarref\Namer\Detector;

use Gaufrette\Adapter\Local;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class GaufretteDetectorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $adapter;

    /**
     * @var GaufretteDetector
     */
    private $detector;

    public function setUp()
    {
        $this->adapter = $this->getMockBuilder('Gaufrette\Adapter\Local')
            ->setConstructorArgs(['/tmp'])
            ->setMethods(['exists'])
            ->getMock();

        $this->detector = new GaufretteDetector($this->adapter);
    }

    public function testAdapterAccessors()
    {
        $new_adapter = new Local('/tmp');

        $set_result = $this->detector->setAdapter($new_adapter);
        $this->assertSame($new_adapter, $this->detector->getAdapter());
        $this->assertSame($this->detector, $set_result, 'Setter is fluent');
    }

    public function testExists()
    {
        $mock_values = [
            'exists'       => 'i_exist',
            'doesnt_exist' => 'dont_exist',
        ];

        $this->adapter->expects($this->exactly(2))
            ->method('exists')
            ->will($this->returnValueMap([
                [$mock_values['exists'], true],
                [$mock_values['doesnt_exist'], false],
            ]));

        $this->assertFalse($this->detector->isAvailable($mock_values['exists']));
        $this->assertTrue($this->detector->isAvailable($mock_values['doesnt_exist']));
    }

    public function tearDown()
    {
        unset(
            $this->adapter,
            $this->detector
        );
    }
}
