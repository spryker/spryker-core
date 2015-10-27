<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Calculator;

use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;

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
