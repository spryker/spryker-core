<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Expander;

use ArrayObject;
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
        if ($expenseTransfer->getType() !== static::SHIPMENT_EXPENSE_TYPE || !$this->isShipmentGroupValid($shipmentGroupTransfer)) {
            return $expenseTransfer;
        }

        /** @var \Generated\Shared\Transfer\ItemTransfer $item */
        $itemTransfer = $shipmentGroupTransfer->getItems()->getIterator()->current();
        $expenseTransfer->setMerchantReference($itemTransfer->getMerchantReference());

        return $expenseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return bool
     */
    protected function isShipmentGroupValid(ShipmentGroupTransfer $shipmentGroupTransfer): bool
    {
        if (!$shipmentGroupTransfer->getItems()->count()) {
            return false;
        }

        $uniqueMerchantReferences = $this->getUniqueMerchantReferences($shipmentGroupTransfer->getItems());

        if (count($uniqueMerchantReferences) !== 1) {
            return false;
        }

        return true;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return string[]
     */
    protected function getUniqueMerchantReferences(ArrayObject $items): array
    {
        $merchantReferences = [];

        foreach ($items as $item) {
            if (!$item->getMerchantReference()) {
                continue;
            }

            $merchantReferences[] = $item->getMerchantReference();
        }

        return array_unique($merchantReferences);
    }
}
