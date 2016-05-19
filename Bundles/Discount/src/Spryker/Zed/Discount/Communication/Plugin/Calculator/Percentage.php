<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\Calculator;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Discount\Communication\Plugin\AbstractDiscountPlugin;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 */
class Percentage extends AbstractDiscountPlugin implements DiscountCalculatorPluginInterface
{

    /**
     * @param DiscountableItemTransfer[] $discountableItems
     * @param float $number
     *
     * @return float
     */
    public function calculate(array $discountableItems, $number)
    {
        return $this->getFacade()
            ->calculatePercentage($discountableItems, $number);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return string
     */
    public function getFormattedAmount(DiscountTransfer $discountTransfer)
    {
        return $discountTransfer->getAmount() . '%';
    }

}
