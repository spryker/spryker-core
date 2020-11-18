<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountMerchantSalesOrder\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class MerchantOrderDiscountFilter implements MerchantOrderDiscountFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function filterMerchantDiscounts(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderTransfer
    {
        $orderTransfer = $merchantOrderTransfer->getOrder();

        if (!$orderTransfer) {
            return $merchantOrderTransfer;
        }

        $generalCalculatedDiscountTransfers = $this->getGeneralDiscounts(
            $orderTransfer,
            $merchantOrderTransfer->getMerchantOrderItems()->count()
        );
        $itemsCalculatedDiscountTransfers = $this->getMerchantOrderItemDiscounts($merchantOrderTransfer);

        $calculatedDiscountTransfers = array_merge($generalCalculatedDiscountTransfers, $itemsCalculatedDiscountTransfers);

        $orderTransfer->setCalculatedDiscounts(
            new ArrayObject($calculatedDiscountTransfers)
        );

        $merchantOrderTransfer->setOrder($orderTransfer);

        return $merchantOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $merchantOrderItemsCount
     *
     * @return \Generated\Shared\Transfer\CalculatedDiscountTransfer[]
     */
    protected function getGeneralDiscounts(OrderTransfer $orderTransfer, $merchantOrderItemsCount): array
    {
        $calculatedDiscountTransfers = [];

        foreach ($orderTransfer->getCalculatedDiscounts()->getArrayCopy() as $calculatedDiscountTransfer) {
            /** @var \Generated\Shared\Transfer\CalculatedDiscountTransfer $calculatedDiscountTransfer */
            if ($calculatedDiscountTransfer->getFkSalesOrderItem()) {
                continue;
            }

            $sumAmount = $calculatedDiscountTransfer->getSumAmount();
            $calculatedQuantity = $calculatedDiscountTransfer->getQuantity();

            $calculatedDiscountTransfer->setSumAmount($sumAmount / $calculatedQuantity * $merchantOrderItemsCount);
            $calculatedDiscountTransfer->setQuantity($merchantOrderItemsCount);
            $calculatedDiscountTransfers[$calculatedDiscountTransfer->getDisplayName()] = $calculatedDiscountTransfer;
        }

        return $calculatedDiscountTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\CalculatedDiscountTransfer[]
     */
    protected function getMerchantOrderItemDiscounts(MerchantOrderTransfer $merchantOrderTransfer): array
    {
        $grouppedCalculatedDiscounts = [];

        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            if (!$merchantOrderItemTransfer->getOrderItem()) {
                continue;
            }

            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $itemTransfer = $merchantOrderItemTransfer->requireOrderItem()->getOrderItem();
            $grouppedCalculatedDiscounts = $this->getGrouppedCalculatedDiscounts(
                $itemTransfer,
                $grouppedCalculatedDiscounts
            );
        }

        return $grouppedCalculatedDiscounts;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CalculatedDiscountTransfer[] $grouppedCalculatedDiscounts
     *
     * @return \Generated\Shared\Transfer\CalculatedDiscountTransfer[]
     */
    protected function getGrouppedCalculatedDiscounts(ItemTransfer $itemTransfer, array $grouppedCalculatedDiscounts): array
    {
        foreach ($itemTransfer->getCalculatedDiscounts() as $calculatedDiscountTransfer) {
            if (!isset($grouppedCalculatedDiscounts[$calculatedDiscountTransfer->getDisplayName()])) {
                $grouppedCalculatedDiscounts[$calculatedDiscountTransfer->getDisplayName()] = $calculatedDiscountTransfer;

                continue;
            }

            $groupedCalculatedDiscountTransfer = $grouppedCalculatedDiscounts[$calculatedDiscountTransfer->getDisplayName()];
            $groupedCalculatedDiscountTransfer->setQuantity(
                $groupedCalculatedDiscountTransfer->getQuantity() + $calculatedDiscountTransfer->getQuantity()
            );
            $groupedCalculatedDiscountTransfer->setSumAmount(
                $groupedCalculatedDiscountTransfer->getSumAmount() + $calculatedDiscountTransfer->getSumAmount()
            );

            $grouppedCalculatedDiscounts[$calculatedDiscountTransfer->getDisplayName()] = $groupedCalculatedDiscountTransfer;
        }

        return $grouppedCalculatedDiscounts;
    }
}
