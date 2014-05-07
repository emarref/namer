<?php

namespace Emarref\Namer\Detector;

interface DetectorInterface
{
    /**
     * Detect and return whether or not this value is available to be used as a name.
     *
     * @param string $value
     * @return boolean
     */
    public function isAvailable($value);
}
