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
        $itemTransfers = $orderTransfer->getItems();
        $restOrderItemsAttributesTransfers = $restOrderDetailsAttributesTransfer->getItems();

        $restOrderItemsAttributesTransfers = $this->mapItemTransfersToRestOrderItemsAttributesTransfers(
            $itemTransfers,
            $restOrderItemsAttributesTransfers
        );

        $expenseTransfers = $orderTransfer->getExpenses();

        $restOrderExpensesAttributesTransfers = $restOrderDetailsAttributesTransfer->getExpenses();

        $restOrderExpensesAttributesTransfers = $this->mapExpenseTransfersToRestOrderExpensesAttributesTransfers(
            $expenseTransfers,
            $restOrderExpensesAttributesTransfers
        );

        return $restOrderDetailsAttributesTransfer
            ->setItems($restOrderItemsAttributesTransfers)
            ->setExpenses($restOrderExpensesAttributesTransfers);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \ArrayObject|\Generated\Shared\Transfer\RestOrderItemsAttributesTransfer[] $restOrderItemsAttributesTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\RestOrderItemsAttributesTransfer[]
     */
    protected function mapItemTransfersToRestOrderItemsAttributesTransfers(
        ArrayObject $itemTransfers,
        ArrayObject $restOrderItemsAttributesTransfers
    ): ArrayObject {
        foreach ($itemTransfers as $itemTransfer) {
            $restOrderItemsAttributesTransfer = $this->findRestOrderItemsAttributesTransferToItemTransferByItemUuid(
                $itemTransfer,
                $restOrderItemsAttributesTransfers
            );
            if (!$restOrderItemsAttributesTransfer) {
                return $restOrderItemsAttributesTransfers;
            }
            $restOrderItemsAttributesTransfer
                ->fromArray($itemTransfer->toArray(), true)
                ->setIdShipment($itemTransfer->getShipment()->getIdSalesShipment());
        }

        return $restOrderItemsAttributesTransfers;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     * @param \ArrayObject|\Generated\Shared\Transfer\RestOrderExpensesAttributesTransfer[] $restOrderExpensesAttributesTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\RestOrderExpensesAttributesTransfer[]
     */
    protected function mapExpenseTransfersToRestOrderExpensesAttributesTransfers(
        ArrayObject $expenseTransfers,
        ArrayObject $restOrderExpensesAttributesTransfers
    ): ArrayObject {
        foreach ($expenseTransfers as $expenseTransfer) {
            $restOrderExpensesAttributesTransfer = $this->findRestOrderExpensesAttributesTransferToExpenseTransferBy(
                $expenseTransfer,
                $restOrderExpensesAttributesTransfers
            );
            if (!$restOrderExpensesAttributesTransfer) {
                return $restOrderExpensesAttributesTransfers;
            }
            $restOrderExpensesAttributesTransfer
                ->fromArray($expenseTransfer->toArray(), true)
                ->setIdShipment($expenseTransfer->getShipment()->getIdSalesShipment());
        }

        return $restOrderExpensesAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\RestOrderItemsAttributesTransfer[] $restOrderItemsAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer|null
     */
    protected function findRestOrderItemsAttributesTransferToItemTransferByItemUuid(
        ItemTransfer $itemTransfer,
        ArrayObject $restOrderItemsAttributesTransfers
    ): ?RestOrderItemsAttributesTransfer {
        foreach ($restOrderItemsAttributesTransfers as $restOrderItemsAttributesTransfer) {
            if ($restOrderItemsAttributesTransfer->getUuid() === $itemTransfer->getUuid()) {
                return $restOrderItemsAttributesTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\RestOrderExpensesAttributesTransfer[] $restOrderExpensesAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestOrderExpensesAttributesTransfer|null
     */
    protected function findRestOrderExpensesAttributesTransferToExpenseTransferBy(
        ExpenseTransfer $expenseTransfer,
        ArrayObject $restOrderExpensesAttributesTransfers
    ): ?RestOrderExpensesAttributesTransfer {
        foreach ($restOrderExpensesAttributesTransfers as $restOrderExpensesAttributesTransfer) {
            if ($restOrderExpensesAttributesTransfer->getIdSalesExpense() === $expenseTransfer->getIdSalesExpense()) {
                return $restOrderExpensesAttributesTransfer;
            }
        }

        return null;
    }
}
