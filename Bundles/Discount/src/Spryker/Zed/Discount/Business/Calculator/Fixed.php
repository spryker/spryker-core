<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Calculator;

class Fixed implements CalculatorInterface
{

    /**
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableItemInterface[] $discountableObjects
     * @param float $percentage
     *
     * @return float
     */
    public function calculate(array $discountableObjects, $percentage)
    {
        if ($percentage <= 0) {
            return 0;
        }

        return $percentage;
    }

}
