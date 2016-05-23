<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Calculator\Type;

use Generated\Shared\Transfer\DiscountableItemTransfer;

interface CalculatorInterface
{

    /**
     * @param DiscountableItemTransfer[] $discountableObjects
     * @param float $value
     *
     * @return float
     */
    public function calculate(array $discountableObjects, $value);

}
