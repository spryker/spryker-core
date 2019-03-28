<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Business\Discount\DiscountableItem;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\DiscountableItemTransformerTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\SalesQuantity\Dependency\Service\SalesQuantityToUtilPriceServiceInterface;

class DiscountableItemTransformer implements DiscountableItemTransformerInterface
{
    /**
     * @var \Spryker\Zed\SalesQuantity\Dependency\Service\SalesQuantityToUtilPriceServiceInterface
     */
    protected $utilPriceService;

    /**
     * @param \Spryker\Zed\SalesQuantity\Dependency\Service\SalesQuantityToUtilPriceServiceInterface $utilPriceService
     */
    public function __construct(SalesQuantityToUtilPriceServiceInterface $utilPriceService)
    {
        $this->utilPriceService = $utilPriceService;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransformerTransfer $discountableItemTransformerTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransformerTransfer
     */
    public function transformNonSplittableDiscountableItem(
        DiscountableItemTransformerTransfer $discountableItemTransformerTransfer
    ): DiscountableItemTransformerTransfer {
        $roundingError = $discountableItemTransformerTransfer->getRoundingError();
        $discountableItemTransfer = $discountableItemTransformerTransfer->getDiscountableItem();
        $discountTransfer = $discountableItemTransformerTransfer->getDiscount();
        $totalDiscountAmount = $discountableItemTransformerTransfer->getTotalDiscountAmount();
        $totalAmount = $discountableItemTransformerTransfer->getTotalAmount();
        $quantity = $discountableItemTransformerTransfer->getQuantity();

        $calculatedDiscountTransfer = $this->createBaseCalculatedDiscountTransfer($discountTransfer);
        $singleItemAmountShare = $discountableItemTransfer->getUnitPrice() * $quantity / $totalAmount;

        $itemDiscountAmount = ($totalDiscountAmount * $singleItemAmountShare) + $roundingError;
        $itemDiscountAmountRounded = $this->roundPrice($itemDiscountAmount);
        $roundingError = $itemDiscountAmount - $itemDiscountAmountRounded;

        $distributedDiscountTransfer = clone $calculatedDiscountTransfer;
        $distributedDiscountTransfer->setIdDiscount($discountTransfer->getIdDiscount());
        $distributedDiscountTransfer->setSumAmount($itemDiscountAmountRounded);
        $unitAmount = $this->roundPrice($itemDiscountAmountRounded / $quantity);
        $distributedDiscountTransfer->setUnitAmount($unitAmount);
        $distributedDiscountTransfer->setQuantity($quantity);

        $discountableItemTransfer->getOriginalItemCalculatedDiscounts()->append($distributedDiscountTransfer);

        $discountableItemTransformerTransfer
            ->setRoundingError($roundingError)
            ->setDiscountableItem($discountableItemTransfer);

        return $discountableItemTransformerTransfer;
    }

    /**
     * @param float $price
     *
     * @return int
     */
    protected function roundPrice(float $price): int
    {
        return $this->utilPriceService->roundPrice($price);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Generated\Shared\Transfer\CalculatedDiscountTransfer
     */
    protected function createBaseCalculatedDiscountTransfer(DiscountTransfer $discountTransfer): CalculatedDiscountTransfer
    {
        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->fromArray($discountTransfer->toArray(), true);

        return $calculatedDiscountTransfer;
    }
}
