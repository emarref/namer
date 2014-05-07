<?php

namespace Emarref\Namer\Strategy;

interface StrategyInterface
{
    /**
     * Given a name, return an incremented value relative to $index.
     *
     * @param string $name
     * @param int $index
     * @return string
     */
    public function getName($name, $index);
}
