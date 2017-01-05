<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Collector;

use ArrayObject;
use Generated\Shared\Transfer\DiscountableItemTransfer;

class BaseCollector
{

    /**
     * @param int $grossPrice
     * @param int $quantity
     * @param \ArrayObject $originalItemCalculatedDiscounts
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer
     */
    protected function createDiscountableItemTransfer($grossPrice, $quantity, ArrayObject $originalItemCalculatedDiscounts)
    {
        $discountableItemTransfer = new DiscountableItemTransfer();
        $discountableItemTransfer->setUnitGrossPrice($grossPrice);
        $discountableItemTransfer->setQuantity($quantity);
        $discountableItemTransfer->setOriginalItemCalculatedDiscounts($originalItemCalculatedDiscounts);

        return $discountableItemTransfer;
    }

}
