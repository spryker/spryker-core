<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;

class RandomNumberGenerator implements RandomNumberGeneratorInterface
{

    protected $min;

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
        return rand($this->min, $this->max);
    }
}
