<?php

namespace Emarref\Namer\Strategy;

/**
 * A simple strategy that returns the name untouched on iteration 0, returns the name with suffix appended on iteration
 * 1, and returns the name with suffix and iteration appended thereafter.
 *
 * e.g.
 * getName('Name', 0) -> Name
 * getName('Name', 1) -> Name copy
 * getName('Name', 2) -> Name copy 2
 * getName('Name', 3) -> Name copy 3
 * [...]
 */
class SuffixStrategy implements StrategyInterface
{
    /**
     * @var string
     */
    private $suffix;

    /**
     * @var boolean
     */
    private $incremental;

    /**
     * @param string $suffix
     * @param bool   $incremental
     */
    public function __construct($suffix = 'copy', $incremental = true)
    {
        $this->setSuffix($suffix);
        $this->setIncremental($incremental);
    }

    /**
     * Set the suffix that will be appended to a name when naming.
     *
     * @param string $suffix
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
    }

    /**
     * Get the suffix that will be appended to a name when naming.
     *
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * Set whether the naming will occur incrementally.
     *
     * @param boolean $incremental
     */
    public function setIncremental($incremental)
    {
        $this->incremental = $incremental;
    }

    /**
     * Get whether the naming will occur incrementally.
     *
     * @return boolean
     */
    public function getIncremental()
    {
        return $this->incremental;
    }

    /**
     * @inheritdoc
     */
    public function getName($name, $index)
    {
        if ($index < 1) {
            // The first try, simply return the name unchanged.
            return $name;
        }

        $suffix = $this->getSuffix();

        if ($this->getIncremental()) {
            // Use suffix, suffix 2, suffix 3 etc
            if ($index > 1) {
                $suffix = sprintf('%s %s', $suffix, $index);
            }
        } else {
            // Use suffix, suffix suffix, suffix suffix suffix etc
            $suffix = implode(' ', array_pad([], $index, $suffix));
        }

        return sprintf('%s %s', $name, $suffix);
    }
}
