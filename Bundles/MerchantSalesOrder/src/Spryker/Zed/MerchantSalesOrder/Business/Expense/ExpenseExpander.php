<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Expense;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;

class ExpenseExpander implements ExpenseExpanderInterface
{
    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE.
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function expandShipmentExpenseWithMerchantReference(
        ExpenseTransfer $expenseTransfer,
        ShipmentGroupTransfer $shipmentGroupTransfer
    ): ExpenseTransfer {
        if ($expenseTransfer->getType() !== static::SHIPMENT_EXPENSE_TYPE) {
            return $expenseTransfer;
        }

        $shipmentGroupTransfer->requireItems();

        return $expenseTransfer->setMerchantReference($this->findExclusiveMerchantReference($shipmentGroupTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return string|null
     */
    protected function findExclusiveMerchantReference(ShipmentGroupTransfer $shipmentGroupTransfer): ?string
    {
        $merchantReferences = [];

        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getMerchantReference()) {
                return null;
            }

            $merchantReferences[] = $itemTransfer->getMerchantReference();
        }

        $uniqueMerchantReferences = array_unique($merchantReferences);

        if (count($uniqueMerchantReferences) !== 1) {
            return null;
        }

        return $uniqueMerchantReferences[0];
    }
}
