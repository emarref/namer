<?php

namespace Emarref\Namer\Detector;

/**
 * Simple detector that checks for the existence of a name in the values of an array.
 */
class ArrayDetector implements DetectorInterface
{
    /**
     * @var array
     */
    private $pool;

    /**
     * @param array $pool
     */
    public function __construct(array $pool)
    {
        $this->pool = $pool;
    }

    /**
     * @inheritdoc
     */
    public function isAvailable($value)
    {
        return !in_array($value, $this->pool);
    }
}
