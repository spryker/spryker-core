<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer;
use Generated\Shared\Transfer\RestOrderExpensesAttributesTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;

class OrderDetailsAttributesMapper implements OrderDetailsAttributesMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer $restOrderDetailsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer
     */
    public function mapOrderTransferToRestOrderDetailsAttributesTransfer(
        OrderTransfer $orderTransfer,
        RestOrderDetailsAttributesTransfer $restOrderDetailsAttributesTransfer
    ): RestOrderDetailsAttributesTransfer {
        $restOrderItemsAttributesTransfers = new ArrayObject();
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $restOrderItemsAttributesTransfer = $this->mapItemTransferToRestOrderItemsAttributesTransfer(
                $itemTransfer,
                new RestOrderItemsAttributesTransfer()
            );

            $restOrderItemsAttributesTransfers->append($restOrderItemsAttributesTransfer);
        }

        $restOrderExpensesAttributesTransfers = new ArrayObject();
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            $restOrderExpensesAttributesTransfer = $this->mapExpenseTransferToRestOrderExpensesAttributesTransfer(
                $expenseTransfer,
                new RestOrderExpensesAttributesTransfer()
            );

            $restOrderExpensesAttributesTransfers->append($restOrderExpensesAttributesTransfer);
        }

        return $restOrderDetailsAttributesTransfer
            ->setItems($restOrderItemsAttributesTransfers)
            ->setExpenses($restOrderExpensesAttributesTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer
     */
    protected function mapItemTransferToRestOrderItemsAttributesTransfer(
        ItemTransfer $itemTransfer,
        RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
    ): RestOrderItemsAttributesTransfer {
        return $restOrderItemsAttributesTransfer
            ->fromArray($itemTransfer->toArray(), true)
            ->setIdShipment($itemTransfer->getShipment()->getIdSalesShipment());
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\RestOrderExpensesAttributesTransfer $restOrderExpensesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderExpensesAttributesTransfer
     */
    protected function mapExpenseTransferToRestOrderExpensesAttributesTransfer(
        ExpenseTransfer $expenseTransfer,
        RestOrderExpensesAttributesTransfer $restOrderExpensesAttributesTransfer
    ): RestOrderExpensesAttributesTransfer {
        return $restOrderExpensesAttributesTransfer
            ->fromArray($expenseTransfer->toArray(), true)
            ->setIdShipment($expenseTransfer->getShipment()->getIdSalesShipment());
    }
}
