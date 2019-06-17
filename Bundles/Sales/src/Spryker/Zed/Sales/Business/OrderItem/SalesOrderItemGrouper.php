<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\OrderItem;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Sales\Dependency\Service\SalesToUtilQuantityServiceInterface;

class SalesOrderItemGrouper implements SalesOrderItemGrouperInterface
{
    /**
     * @var \Spryker\Zed\Sales\Dependency\Service\SalesToUtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Service\SalesToUtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct(SalesToUtilQuantityServiceInterface $utilQuantityService)
    {
        $this->utilQuantityService = $utilQuantityService;
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getUniqueOrderItems(iterable $itemTransfers): array
    {
        $calculatedOrderItems = [];
        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer->requireGroupKey();
            $key = $itemTransfer->getGroupKey();
            if (!isset($calculatedOrderItems[$key])) {
                $calculatedOrderItems[$key] = clone $itemTransfer;
                continue;
            }

            $calculatedOrderItems[$key] = $this->setQuantityAndPriceOfUniqueOrderItem($calculatedOrderItems[$key], $itemTransfer);
        }

        return $calculatedOrderItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $calculatedOrderItem
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function setQuantityAndPriceOfUniqueOrderItem(ItemTransfer $calculatedOrderItem, ItemTransfer $itemTransfer): ItemTransfer
    {
        $newQuantity = $this->utilQuantityService->sumQuantities(
            $calculatedOrderItem->getQuantity(),
            $itemTransfer->getQuantity()
        );

        $calculatedOrderItem->setQuantity($newQuantity);
        $calculatedOrderItem->setSumPrice($calculatedOrderItem->getSumPrice() + $itemTransfer->getSumPrice());

        return $calculatedOrderItem;
    }
}
