<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Zed\Shipment\ShipmentConfig;

class ShipmentTotalCalculator implements ShipmentTotalCalculatorInterface
{
    /**
     * @var \Spryker\Zed\Shipment\ShipmentConfig
     */
    protected $shipmentConfig;

    /**
     * @param \Spryker\Zed\Shipment\ShipmentConfig $shipmentConfig
     */
    public function __construct(ShipmentConfig $shipmentConfig)
    {
        $this->shipmentConfig = $shipmentConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateShipmentTotal(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $calculableObjectTransfer->requireTotals();

        $shipmentTotal = $this->getShipmentTotalSumPrice($calculableObjectTransfer->getExpenses());

        $calculableObjectTransfer
            ->getTotals()
            ->setShipmentTotal($shipmentTotal);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return int
     */
    protected function getShipmentTotalSumPrice(ArrayObject $expenseTransfers): int
    {
        $shipmentTotal = 0;

        foreach ($expenseTransfers as $expenseTransfer) {
            if ($expenseTransfer->getType() !== $this->shipmentConfig->getShipmentExpenseType()) {
                continue;
            }

            $shipmentTotal += $expenseTransfer->getSumPrice();
        }

        return $shipmentTotal;
    }
}
