<?php

namespace Emarref\Namer;

use Emarref\Namer\Strategy\StrategyInterface;
use Emarref\Namer\Detector\DetectorInterface;

class Namer
{
    const STRATEGY_DEFAULT_LIMIT = 100;

    /**
     * @var StrategyInterface
     */
    private $strategy;

    /**
     * @var DetectorInterface
     */
    private $detector;

    /**
     * @var int
     */
    private $limit;

    /**
     * @param StrategyInterface $strategy
     * @param DetectorInterface $detector
     * @param int               $limit
     */
    public function __construct(
        StrategyInterface $strategy,
        DetectorInterface $detector,
        $limit = self::STRATEGY_DEFAULT_LIMIT
    ) {
        $this->strategy = $strategy;
        $this->detector = $detector;
        $this->setLimit($limit);
    }

    /**
     * Set the number of tries to get an available name before throwing an exception.
     *
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * Get the number of tries to get an available name before throwing an exception.
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Given a name, use the detector to determine if the name is available to be used. If not, use the strategy to
     * generate a new name, then use the detector to determine if that name is available to be used. Continue until
     * either an available name has been found, or the limit has been reached.
     *
     * @param string $name
     * @return string
     * @throws \RuntimeException
     */
    public function getName($name)
    {
        $i = 1;
        $limit = $this->getLimit();

        $originalName = $name;

        while (!$this->detector->isAvailable($name)) {
            $name = $this->strategy->getName($originalName, $i);

            $i++;

            if ($i > $limit) {
                throw new \RuntimeException(sprintf(
                    'Could not find available name for "%s" after %d attempts.',
                    $originalName,
                    $limit
                ));
            }
        }

        return $name;
    }
}
