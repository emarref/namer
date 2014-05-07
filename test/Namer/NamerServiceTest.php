<?php

namespace Emarref\Namer;

class NamerTest extends \PHPUnit_Framework_TestCase
{
    const NAMING_LIMIT = 100;

    public function testGetNameAvailable()
    {
        $name = 'Namer';

        $strategy = $this->getMockStrategy();

        $strategy->expects($this->never())
            ->method('getName');

        $detector = $this->getMockDetector();

        $detector->expects($this->once())
            ->method('isAvailable')
            ->with($name)
            ->will($this->returnValue(true));

        $namer = new Namer($strategy, $detector, self::NAMING_LIMIT);
        $this->assertSame($name, $namer->getName($name), 'Available name is unchanged');
    }

    public function testGetNameUnavailable()
    {
        $name      = 'Namer';
        $copyName = sprintf('%s %s', $name, 'copy');

        $strategy = $this->getMockStrategy();

        $strategy->expects($this->once())
            ->method('getName')
            ->with($name, 1)
            ->will($this->returnValue($copyName));

        $detector = $this->getMockDetector();

        $detector->expects($this->exactly(2))
            ->method('isAvailable')
            ->will($this->returnValueMap([
                [$name, false],
                [$copyName, true]
            ]));

        $namer = new Namer($strategy, $detector, self::NAMING_LIMIT);
        $this->assertSame($copyName, $namer->getName($name), 'Unavailable name has suffix appended');
    }

    public function testGetNameUnavailableIncremented()
    {
        $name             = 'Namer';
        $copyName         = sprintf('%s %s', $name, 'copy');
        $incrementedName  = sprintf('%s %d', $copyName, 2);

        $strategy = $this->getMockStrategy();

        $strategy->expects($this->exactly(2))
            ->method('getName')
            ->will($this->returnValueMap([
                [$name, 1, $copyName],
                [$name, 2, $incrementedName]
            ]));

        $detector = $this->getMockDetector();

        $detector->expects($this->exactly(3))
            ->method('isAvailable')
            ->will($this->returnValueMap([
                [$name, false],
                [$copyName, false],
                [$incrementedName, true]
            ]));

        $namer = new Namer($strategy, $detector, self::NAMING_LIMIT);
        $this->assertSame($incrementedName, $namer->getName($name), 'Unavailable name has suffix appended and incremented');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Could not find available name for "Namer" after 100 attempts.
     */
    public function testUnavailableName()
    {
        $strategy = $this->getMockStrategy();

        $strategy->expects($this->exactly(self::NAMING_LIMIT))
            ->method('getName');

        $detector = $this->getMockDetector();

        $detector->expects($this->exactly(self::NAMING_LIMIT))
            ->method('isAvailable')
            ->will($this->returnValue(false));

        $namer = new Namer($strategy, $detector, self::NAMING_LIMIT);
        $namer->getName('Namer');
    }

    private function getMockStrategy()
    {
        return $this->getMock('Emarref\Namer\Strategy\SuffixStrategy', ['getName']);
    }

    private function getMockDetector()
    {
        return $this->getMock('Emarref\Namer\Detector\ArrayDetector', ['isAvailable'], [[]]);
    }
}
