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
     * @var boolean
     */
    private $ignoreExtension;

    /**
     * @param string $suffix
     * @param bool   $incremental
     * @param bool   $ignoreExtension
     */
    public function __construct($suffix = 'copy', $incremental = true, $ignoreExtension = true)
    {
        $this->setSuffix($suffix);
        $this->setIncremental($incremental);
        $this->setIgnoreExtension($ignoreExtension);
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
     * Set whether to insert the suffix **before** the file extension
     *
     * @param boolean $ignoreExtension
     */
    public function setIgnoreExtension($ignoreExtension)
    {
        $this->ignoreExtension = $ignoreExtension;
    }

    /**
     * Get whether to insert the suffix **before** the file extension
     *
     * @return boolean
     */
    public function getIgnoreExtension()
    {
        return $this->ignoreExtension;
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

        // We are preceding the extension with our suffix, **and** the
        // file does already have an extension
        if (!$this->getIgnoreExtension() && ($extension = pathinfo($name, PATHINFO_EXTENSION))) {
            $name = preg_replace('/(\.'.preg_quote($extension).')$/', '', $name);

            return sprintf('%s %s.%s', $name, $suffix, $extension);
        }

        return sprintf('%s %s', $name, $suffix);
    }
}
