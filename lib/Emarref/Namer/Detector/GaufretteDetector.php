<?php

namespace Emarref\Namer\Detector;

use Gaufrette\Adapter;

/**
 * A detector which determines whether or not a file exists using a Gaufrette Adapter
 */
class GaufretteDetector implements DetectorInterface
{
    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * @param Adapter $adapter
     */
    function __construct(Adapter $adapter)
    {
        $this->setAdapter($adapter);
    }

    /**
     * @param Adapter $adapter
     * @return GaufretteDetector
     */
    public function setAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * @return Adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @inheritdoc
     */
    public function isAvailable($value)
    {
        return !$this->adapter->exists($value);
    }
}
