<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\Calculator;

use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountAmountCalculatorPluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
abstract class AbstractCalculatorPlugin extends AbstractPlugin implements DiscountCalculatorPluginInterface, DiscountAmountCalculatorPluginInterface
{

    /**
     * @deprecated use calculateDiscount instead
     *
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     * @param int $percentage
     *
     * @return float
     */
    public function calculate(array $discountableItems, $percentage)
    {
        $discountTransfer = (new DiscountTransfer())->setAmount($percentage);
        return $this->calculateDiscount($discountableItems, $discountTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     * @param \Generated\Shared\Transfer\DiscountTransfer$discountTransfer
     *
     * @return int
     */
    abstract public function calculateDiscount(array $discountableItems, DiscountTransfer $discountTransfer);

}
