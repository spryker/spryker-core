<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;

class CollectedDiscountItemFilter implements CollectedDiscountItemFilterInterface
{
    /**
     * @var int
     */
    protected const DEFAULT_DISCOUNT_AMOUNT = 0;

    /**
     * @var string
     */
    protected const IS_DISCOUNT_APPLICABLE = 'IS_DISCOUNT_APPLICABLE';

    /**
     * @var string
     */
    protected const DISCOUNT_AMOUNT_TO_REDUCE = 'DISCOUNT_AMOUNT_TO_REDUCE';

    /**
     * @var array<int, int>
     */
    protected $remainingUnitPricesByItemIds;

    /**
     * @param array<\Generated\Shared\Transfer\CollectedDiscountTransfer> $collectedDiscountTransfers
     *
     * @return array<\Generated\Shared\Transfer\CollectedDiscountTransfer>
     */
    public function filter(array $collectedDiscountTransfers): array
    {
        $this->remainingUnitPricesByItemIds = [];
        $filteredCollectedDiscountTransfers = [];

        foreach ($collectedDiscountTransfers as $collectedDiscountTransfer) {
            $filteredCollectedDiscountTransfer = $this->filterCollectedDiscountWithItems($collectedDiscountTransfer);

            if ($this->isCollectedDiscountValid($filteredCollectedDiscountTransfer)) {
                $filteredCollectedDiscountTransfers[] = $filteredCollectedDiscountTransfer;
            }
        }

        return $filteredCollectedDiscountTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return \Generated\Shared\Transfer\CollectedDiscountTransfer
     */
    protected function filterCollectedDiscountWithItems(CollectedDiscountTransfer $collectedDiscountTransfer): CollectedDiscountTransfer
    {
        $discountTransfer = $collectedDiscountTransfer->getDiscount();
        if (!$discountTransfer) {
            return $collectedDiscountTransfer;
        }

        $totalDiscountAmountToReduce = static::DEFAULT_DISCOUNT_AMOUNT;

        $validDiscountableItemTransfers = [];
        foreach ($collectedDiscountTransfer->getDiscountableItems() as $discountableItemTransfer) {
            $processResult = $this->processDiscountableItem($discountableItemTransfer, $discountTransfer);

            $totalDiscountAmountToReduce += $processResult[static::DISCOUNT_AMOUNT_TO_REDUCE];

            if ($processResult[static::IS_DISCOUNT_APPLICABLE]) {
                $validDiscountableItemTransfers[] = $discountableItemTransfer;
            }
        }

        $collectedDiscountTransfer->setDiscountableItems(new ArrayObject($validDiscountableItemTransfers));

        $discountTransfer->setAmount($discountTransfer->getAmount() - $totalDiscountAmountToReduce);
        $collectedDiscountTransfer->setDiscount($discountTransfer);

        return $collectedDiscountTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return array<string, mixed>
     */
    protected function processDiscountableItem(DiscountableItemTransfer $discountableItemTransfer, DiscountTransfer $discountTransfer): array
    {
        $discountableItemProcessResult = [
            static::IS_DISCOUNT_APPLICABLE => true,
            static::DISCOUNT_AMOUNT_TO_REDUCE => static::DEFAULT_DISCOUNT_AMOUNT,
        ];

        $originalItemTransfer = $discountableItemTransfer->getOriginalItem();
        if (!$originalItemTransfer) {
            return $discountableItemProcessResult;
        }

        $originalItemId = $originalItemTransfer->getId();
        if (!$originalItemId) {
            return $discountableItemProcessResult;
        }

        if (!isset($this->remainingUnitPricesByItemIds[$originalItemId])) {
            $this->remainingUnitPricesByItemIds[$originalItemId] = $discountableItemTransfer->getUnitPrice();
        }

        foreach ($discountableItemTransfer->getOriginalItemCalculatedDiscounts() as $originalItemCalculatedDiscountTransfer) {
            $discountableItemProcessResult = $this->processDiscountableItemCalculatedDiscount(
                $originalItemCalculatedDiscountTransfer,
                $discountTransfer,
                $originalItemId,
                $discountableItemProcessResult,
            );
        }

        return $discountableItemProcessResult;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculatedDiscountTransfer $originalItemCalculatedDiscountTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param int $originalItemId
     * @param array<string, mixed> $discountableItemProcessResult
     *
     * @return array<string, mixed>
     */
    protected function processDiscountableItemCalculatedDiscount(
        CalculatedDiscountTransfer $originalItemCalculatedDiscountTransfer,
        DiscountTransfer $discountTransfer,
        int $originalItemId,
        array $discountableItemProcessResult
    ): array {
        if ($originalItemCalculatedDiscountTransfer->getIdDiscount() !== $discountTransfer->getIdDiscount()) {
            return $discountableItemProcessResult;
        }

        $discountAmount = $originalItemCalculatedDiscountTransfer->getUnitAmount();

        if (!$this->remainingUnitPricesByItemIds[$originalItemId]) {
            $discountableItemProcessResult[static::IS_DISCOUNT_APPLICABLE] = false;
            $discountableItemProcessResult[static::DISCOUNT_AMOUNT_TO_REDUCE] += $discountAmount;

            return $discountableItemProcessResult;
        }

        if ($discountAmount <= $this->remainingUnitPricesByItemIds[$originalItemId]) {
            $this->remainingUnitPricesByItemIds[$originalItemId] -= $discountAmount;

            return $discountableItemProcessResult;
        }

        $discountableItemProcessResult[static::DISCOUNT_AMOUNT_TO_REDUCE] += $discountAmount - $this->remainingUnitPricesByItemIds[$originalItemId];
        $this->remainingUnitPricesByItemIds[$originalItemId] = static::DEFAULT_DISCOUNT_AMOUNT;

        return $discountableItemProcessResult;
    }

    /**
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return bool
     */
    protected function isCollectedDiscountValid(CollectedDiscountTransfer $collectedDiscountTransfer): bool
    {
        if (!$collectedDiscountTransfer->getDiscountableItems()->count()) {
            return false;
        }

        if (!$collectedDiscountTransfer->getDiscount()) {
            return true;
        }

        return $collectedDiscountTransfer->getDiscount()->getAmount() > static::DEFAULT_DISCOUNT_AMOUNT;
    }
}
