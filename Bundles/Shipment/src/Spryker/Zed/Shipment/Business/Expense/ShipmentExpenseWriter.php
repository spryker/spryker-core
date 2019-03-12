<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Expense;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;

class ShipmentExpenseWriter implements ShipmentExpenseWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function removeObsoleteShipmentExpenses(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $quoteTransfer = $calculableObjectTransfer->getOriginalQuote();
        if ($quoteTransfer === null || $this->isShipmentMethodSet($quoteTransfer) === true) {
            return;
        }

        $calculableObjectTransfer->setExpenses(
            $this->filterExpenseTransferCollectionByExpenseType($calculableObjectTransfer->getExpenses())
        );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransferCollection
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[]
     */
    protected function filterExpenseTransferCollectionByExpenseType(ArrayObject $expenseTransferCollection): ArrayObject
    {
        $filteredExpenseTransferCollection = new ArrayObject();
        foreach ($expenseTransferCollection as $expenseTransfer) {
            if ($expenseTransfer->getType() !== ShipmentConstants::SHIPMENT_EXPENSE_TYPE) {
                $filteredExpenseTransferCollection->append($expenseTransfer);
            }
        }

        return $filteredExpenseTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isShipmentMethodSet(QuoteTransfer $quoteTransfer): bool
    {
        $shipmentTransfer = $quoteTransfer->getShipment();
        if ($shipmentTransfer === null) {
            return false;
        }

        $shipmentMethodTransfer = $shipmentTransfer->getMethod();
        if ($shipmentMethodTransfer === null || $shipmentMethodTransfer->getIdShipmentMethod() === null) {
            return false;
        }

        return true;
    }
}
