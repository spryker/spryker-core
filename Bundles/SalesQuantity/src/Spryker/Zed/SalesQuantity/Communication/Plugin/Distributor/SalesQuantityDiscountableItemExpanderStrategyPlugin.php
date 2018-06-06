<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Communication\Plugin\Distributor;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\Distributor\DiscountableItemExpanderStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class SalesQuantityDiscountableItemExpanderStrategyPlugin extends AbstractPlugin implements DiscountableItemExpanderStrategyPluginInterface
{
    /**
     * @var float
     */
    protected $roundingError = 0.0;

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     *
     * @return bool
     */
    public function isApplicable(DiscountableItemTransfer $discountableItemTransfer): bool
    {
        return !$discountableItemTransfer->getOriginalItem()->getIsQuantitySplittable();
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param int $totalDiscountAmount
     * @param int $totalAmount
     * @param int $quantity
     *
     * @return void
     */
    public function expandDiscountableItem(DiscountableItemTransfer $discountableItemTransfer, DiscountTransfer $discountTransfer, $totalDiscountAmount, $totalAmount, $quantity)
    {
        $calculatedDiscountTransfer = $this->createBaseCalculatedDiscountTransfer($discountTransfer);
        $singleItemAmountShare = $discountableItemTransfer->getUnitPrice() * $quantity / $totalAmount;

        $itemDiscountAmount = ($totalDiscountAmount * $singleItemAmountShare) + $this->roundingError;
        $itemDiscountAmountRounded = (int)round($itemDiscountAmount);
        $this->roundingError = $itemDiscountAmount - $itemDiscountAmountRounded;

        $distributedDiscountTransfer = clone $calculatedDiscountTransfer;
        $distributedDiscountTransfer->setIdDiscount($discountTransfer->getIdDiscount());
        $distributedDiscountTransfer->setUnitAmount($itemDiscountAmountRounded);
        $distributedDiscountTransfer->setQuantity(1);

        $discountableItemTransfer->getOriginalItemCalculatedDiscounts()->append($distributedDiscountTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Generated\Shared\Transfer\CalculatedDiscountTransfer
     */
    protected function createBaseCalculatedDiscountTransfer(DiscountTransfer $discountTransfer)
    {
        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->fromArray($discountTransfer->toArray(), true);

        return $calculatedDiscountTransfer;
    }
}
