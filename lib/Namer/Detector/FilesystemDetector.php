<?php

namespace Emarref\Namer\Detector;

/**
 * Simple detector that determines whether or not a file exists on the filesystem.
 */
class FilesystemDetector implements DetectorInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path
     */
    function __construct($path)
    {
        $this->setPath($path);
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @inheritdoc
     */
    public function isAvailable($value)
    {
        $path = $this->getPath();

        $filename = sprintf('%s%s%s', $path, DIRECTORY_SEPARATOR, $value);

        return !file_exists($filename);
    }
} 
