<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Calculator\Type;

class Fixed implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     * @param float $value
     *
     * @return float
     */
    public function calculate(array $discountableItems, $value)
    {
        if ($value <= 0) {
            return 0;
        }

        return $value;
    }

}
