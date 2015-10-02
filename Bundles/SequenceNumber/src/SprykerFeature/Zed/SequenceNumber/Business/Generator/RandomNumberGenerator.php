<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SequenceNumber\Business\Generator;

class RandomNumberGenerator implements RandomNumberGeneratorInterface
{

    /** @var int */
    protected $min;

    /** @var int */
    protected $max;

    /**
     * @param int $min
     * @param int $max
     */
    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @return int
     */
    public function generate()
    {
        if ($this->min === $this->max) {
            return $this->max;
        }

        return rand($this->min, $this->max);
    }

}
