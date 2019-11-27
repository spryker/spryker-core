<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SequenceNumber\Business\Generator;

class RandomNumberGenerator implements RandomNumberGeneratorInterface
{
    /**
     * @var int
     */
    protected $min;

    /**
     * @var int
     */
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

        return random_int($this->min, $this->max);
    }
}
