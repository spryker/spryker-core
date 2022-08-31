<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Distributor\DiscountableItem;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountableItemTransformerTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface;

class DiscountableItemTransformer implements DiscountableItemTransformerInterface
{
    /**
     * @uses \Spryker\Zed\Discount\DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE
     *
     * @var string
     */
    protected const PLUGIN_CALCULATOR_PERCENTAGE = 'PLUGIN_CALCULATOR_PERCENTAGE';

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface
     */
    protected $discountRepository;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface $discountRepository
     */
    public function __construct(DiscountRepositoryInterface $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransformerTransfer $discountableItemTransformerTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransformerTransfer
     */
    public function transformSplittableDiscountableItem(
        DiscountableItemTransformerTransfer $discountableItemTransformerTransfer
    ): DiscountableItemTransformerTransfer {
        $roundingError = $discountableItemTransformerTransfer->getRoundingError();
        $discountableItemTransfer = $discountableItemTransformerTransfer->getDiscountableItem();
        $discountTransfer = $discountableItemTransformerTransfer->getDiscount();
        $totalDiscountAmount = $discountableItemTransformerTransfer->getTotalDiscountAmount();
        $quantity = $discountableItemTransformerTransfer->getQuantity();

        $calculatedDiscountTransfer = $this->createBaseCalculatedDiscountTransfer($discountTransfer);

        $iterationUnitPrice = (int)$discountableItemTransfer->getUnitPrice();
        if ($this->isDiscountPriorityIterationApplicable($discountableItemTransfer, $discountTransfer)) {
            $iterationUnitPrice = $this->calculatePriorityIterationUnitPrice($discountableItemTransfer, $discountTransfer, $iterationUnitPrice);
        }

        $singleItemAmountShare = $iterationUnitPrice / $discountableItemTransformerTransfer->getTotalAmount();

        for ($i = 0; $i < $quantity; $i++) {
            $itemDiscountAmount = ($totalDiscountAmount * $singleItemAmountShare) + $roundingError;
            $itemDiscountAmountRounded = (int)round($itemDiscountAmount);
            $roundingError = $itemDiscountAmount - $itemDiscountAmountRounded;

            $distributedDiscountTransfer = clone $calculatedDiscountTransfer;
            $distributedDiscountTransfer->setIdDiscount($discountTransfer->getIdDiscount());
            $distributedDiscountTransfer->setSumAmount($itemDiscountAmountRounded);
            $distributedDiscountTransfer->setUnitAmount($itemDiscountAmountRounded);
            $distributedDiscountTransfer->setQuantity(1);

            $isCalculatedDiscountAddable = $this->isCalculatedDiscountAddable(
                $discountableItemTransfer,
                $distributedDiscountTransfer,
            );

            if ($isCalculatedDiscountAddable) {
                $discountableItemTransfer->getOriginalItemCalculatedDiscounts()->append($distributedDiscountTransfer);
            }
        }

        $discountableItemTransformerTransfer
            ->setRoundingError($roundingError)
            ->setDiscountableItem($discountableItemTransfer);

        return $discountableItemTransformerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     * @param \Generated\Shared\Transfer\CalculatedDiscountTransfer $distributedCalculatedDiscountTransfer
     *
     * @return bool
     */
    protected function isCalculatedDiscountAddable(
        DiscountableItemTransfer $discountableItemTransfer,
        CalculatedDiscountTransfer $distributedCalculatedDiscountTransfer
    ): bool {
        if ($discountableItemTransfer->getOriginalItem()) {
            return true;
        }

        foreach ($discountableItemTransfer->getOriginalItemCalculatedDiscounts() as $calculatedDiscountTransfer) {
            if ($this->isSameVoucherCode($calculatedDiscountTransfer, $distributedCalculatedDiscountTransfer)) {
                return false;
            }

            if ($calculatedDiscountTransfer->getIdDiscount() === $distributedCalculatedDiscountTransfer->getIdDiscount()) {
                return false;
            }
        }

        return true;
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

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param int $iterationUnitPrice
     *
     * @return int
     */
    protected function calculatePriorityIterationUnitPrice(
        DiscountableItemTransfer $discountableItemTransfer,
        DiscountTransfer $discountTransfer,
        int $iterationUnitPrice
    ): int {
        $usedCalculatedDiscounts = [];
        foreach ($discountableItemTransfer->getOriginalItemCalculatedDiscounts() as $calculatedDiscountTransfer) {
            if (
                $calculatedDiscountTransfer->getPriority() < $discountTransfer->getPriority()
                && !in_array($calculatedDiscountTransfer->getIdDiscount(), $usedCalculatedDiscounts, true)
            ) {
                $iterationUnitPrice -= $calculatedDiscountTransfer->getUnitAmount();
                $usedCalculatedDiscounts[] = $calculatedDiscountTransfer->getIdDiscount();
            }
        }

        return $iterationUnitPrice;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return bool
     */
    protected function isDiscountPriorityIterationApplicable(
        DiscountableItemTransfer $discountableItemTransfer,
        DiscountTransfer $discountTransfer
    ): bool {
        if ($discountTransfer->getCalculatorPlugin() !== static::PLUGIN_CALCULATOR_PERCENTAGE || !$this->discountRepository->hasPriorityField()) {
            return false;
        }

        foreach ($discountableItemTransfer->getOriginalItemCalculatedDiscounts() as $calculatedDiscountTransfer) {
            if ($calculatedDiscountTransfer->getPriority() !== null) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculatedDiscountTransfer $calculatedDiscountTransfer
     * @param \Generated\Shared\Transfer\CalculatedDiscountTransfer $distributedCalculatedDiscountTransfer
     *
     * @return bool
     */
    protected function isSameVoucherCode(
        CalculatedDiscountTransfer $calculatedDiscountTransfer,
        CalculatedDiscountTransfer $distributedCalculatedDiscountTransfer
    ): bool {
        return $calculatedDiscountTransfer->getVoucherCode() !== null
            && $distributedCalculatedDiscountTransfer->getVoucherCode() !== null
            && $calculatedDiscountTransfer->getVoucherCode() === $distributedCalculatedDiscountTransfer->getVoucherCode();
    }
}
