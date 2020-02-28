<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\SalesReturn\Business\Calculator\ReturnTotalCalculatorInterface;

class ReturnExpander implements ReturnExpanderInterface
{
    /**
     * @var \Spryker\Zed\SalesReturn\Business\Calculator\ReturnTotalCalculatorInterface
     */
    protected $returnTotalCalculator;

    /**
     * @param \Spryker\Zed\SalesReturn\Business\Calculator\ReturnTotalCalculatorInterface $returnTotalCalculator
     */
    public function __construct(ReturnTotalCalculatorInterface $returnTotalCalculator)
    {
        $this->returnTotalCalculator = $returnTotalCalculator;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    public function expandReturn(ReturnTransfer $returnTransfer): ReturnTransfer
    {
        $returnTransfer->setReturnTotals(
            $this->returnTotalCalculator->calculateReturnTotals($returnTransfer)
        );

        return $returnTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    public function expandReturnItemsBeforeCreate(ReturnTransfer $returnTransfer, ArrayObject $itemTransfers): ReturnTransfer
    {
        $returnTransfer->requireIdSalesReturn();

        $indexedItemsById = $this->indexOrderItemsById($itemTransfers);
        $indexedItemsByUuid = $this->indexOrderItemsByUuid($itemTransfers);

        foreach ($returnTransfer->getReturnItems() as $returnItemTransfer) {
            $itemTransfer = $returnItemTransfer->getOrderItem();

            $returnItemTransfer->setIdSalesReturn($returnTransfer->getIdSalesReturn());
            $returnItemTransfer->setOrderItem(
                $indexedItemsById[$itemTransfer->getIdSalesOrderItem()] ?? $indexedItemsByUuid[$itemTransfer->getUuid()]
            );
        }

        return $returnTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function indexOrderItemsByUuid(ArrayObject $itemTransfers): array
    {
        $indexedOrderItems = [];

        foreach ($itemTransfers as $itemTransfer) {
            $indexedOrderItems[$itemTransfer->getUuid()] = $itemTransfer;
        }

        return $indexedOrderItems;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function indexOrderItemsById(ArrayObject $itemTransfers): array
    {
        $indexedOrderItems = [];

        foreach ($itemTransfers as $itemTransfer) {
            $indexedOrderItems[$itemTransfer->getIdSalesOrderItem()] = $itemTransfer;
        }

        return $indexedOrderItems;
    }
}
