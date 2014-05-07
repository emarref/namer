<?php

namespace Emarref\Namer\Detector;

class FilesystemDetectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FilesystemDetector
     */
    private $detector;

    public function setup()
    {
        $path = $this->getPath();

        $this->detector = new FilesystemDetector($path);
    }

    public function testIsAvailable()
    {
        $this->assertTrue($this->detector->isAvailable('nofile.txt'), 'File doesn\'t exist');
        $this->assertFalse($this->detector->isAvailable('filename.txt'), 'File does exist');
    }

    private function getPath()
    {
        return realpath(__DIR__.'/../../data');
    }

    public function tearDown()
    {
        unset($this->detector);
    }
} 
