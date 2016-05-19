<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Calculator;

interface CalculatorInterface
{

    /**
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableItemInterface[] $discountableObjects
     * @param float $value
     *
     * @return float
     */
    public function calculate(array $discountableObjects, $value);

}
