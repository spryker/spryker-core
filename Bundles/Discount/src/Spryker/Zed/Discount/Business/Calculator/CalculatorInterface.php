<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Calculator;

interface CalculatorInterface
{

    /**
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableInterface[] $discountableObjects
     * @param float $value
     *
     * @return float
     */
    public function calculate(array $discountableObjects, $value);

}
