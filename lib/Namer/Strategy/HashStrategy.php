<?php

namespace Emarref\Namer\Strategy;

/**
 * Simple strategy that returns a hashed version of a given name and index.
 */
class HashStrategy implements StrategyInterface
{
    const HASHING_STRATEGY_SHA1 = 'sha1';
    const HASHING_STRATEGY_MD5  = 'md5';

    public static $hashingStrategies = [
        self::HASHING_STRATEGY_MD5,
        self::HASHING_STRATEGY_SHA1,
    ];

    /**
     * @var string
     */
    private $hashingStrategy;

    /**
     * @param string $hashingStrategy
     */
    public function __construct($hashingStrategy = self::HASHING_STRATEGY_SHA1)
    {
        $this->setHashingStrategy($hashingStrategy);
    }

    /**
     * Set the hashing strategy used to generate a new name. Can be any value listed in self::$hashingStrategies.
     *
     * @param string $hashingStrategy
     * @throws \InvalidArgumentException
     */
    public function setHashingStrategy($hashingStrategy)
    {
        if (!in_array($hashingStrategy, self::$hashingStrategies)) {
            throw new \InvalidArgumentException(sprintf('Hashing strategy "%s" is not supported.', $hashingStrategy));
        }

        $this->hashingStrategy = $hashingStrategy;
    }

    /**
     * Get the hashing strategy used to generate a new name. Will be a value listed in self::$hashingStrategies.
     *
     * @return string
     */
    public function getHashingStrategy()
    {
        return $this->hashingStrategy;
    }

    /**
     * Return a callback that is used to hash the name.
     *
     * @return string
     */
    protected function getHashingStrategyCallback()
    {
        $hashingStrategy = $this->getHashingStrategy();

        switch ($hashingStrategy) {
            // The following strategies map directly to functions
            case self::HASHING_STRATEGY_SHA1:
            case self::HASHING_STRATEGY_MD5:
                $callback = $hashingStrategy;
                break;
            default:
                throw new \RuntimeException(sprintf('Could not determine callback for strategy "%s"', $hashingStrategy));
        }

        return $callback;
    }

    /**
     * @inheritdoc
     */
    public function getName($name, $index)
    {
        $callback = $this->getHashingStrategyCallback();

        return call_user_func($callback, $name . $index);
    }
}
