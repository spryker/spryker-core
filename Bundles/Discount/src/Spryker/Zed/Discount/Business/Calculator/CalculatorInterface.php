<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Calculator;

use Spryker\Zed\Discount\Business\Model\DiscountableInterface;

interface CalculatorInterface
{

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $value
     *
     * @return float
     */
    public function calculate(array $discountableObjects, $value);

}
