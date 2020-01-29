<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ShipmentTotalCalculator implements ShipmentTotalCalculatorInterface
{
    /**
     * @see \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateShipmentTotal(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $calculableObjectTransfer->requireTotals();

        $quoteTransfer = $calculableObjectTransfer->getOriginalQuote();

        $calculableObjectTransfer
            ->getTotals()
            ->setShipmentTotal($this->getShipmentTotalSumPrice($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getShipmentTotalSumPrice(QuoteTransfer $quoteTransfer): int
    {
        $shipmentTotal = 0;

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() !== static::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $shipmentTotal += $expenseTransfer->getSumPrice();
        }

        return $shipmentTotal;
    }
}
