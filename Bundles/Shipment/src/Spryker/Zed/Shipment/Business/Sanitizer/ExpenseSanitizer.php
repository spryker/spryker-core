<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Sanitizer;

use Generated\Shared\Transfer\ExpenseTransfer;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToPriceFacadeInterface;

class ExpenseSanitizer implements ExpenseSanitizerInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToPriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToPriceFacadeInterface $priceFacade
     */
    public function __construct(ShipmentToPriceFacadeInterface $priceFacade)
    {
        $this->priceFacade = $priceFacade;
    }

    /**
     * @deprecated @deprecated For BC reasons the missing sum prices are mirrored from unit prices.
     *
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function sanitizeExpenseSumValues(ExpenseTransfer $expenseTransfer): ExpenseTransfer
    {
        $expenseTransfer->setSumGrossPrice($expenseTransfer->getSumGrossPrice() ?? $expenseTransfer->getUnitGrossPrice());
        $expenseTransfer->setSumNetPrice($expenseTransfer->getSumNetPrice() ?? $expenseTransfer->getUnitNetPrice());
        $expenseTransfer->setSumPrice($expenseTransfer->getSumPrice() ?? $expenseTransfer->getUnitPrice());
        $expenseTransfer->setSumTaxAmount($expenseTransfer->getSumTaxAmount() ?? $expenseTransfer->getUnitTaxAmount());
        $expenseTransfer->setSumDiscountAmountAggregation(
            $expenseTransfer->getSumDiscountAmountAggregation()
            ?? $expenseTransfer->getUnitDiscountAmountAggregation()
        );
        $expenseTransfer->setSumPriceToPayAggregation(
            $expenseTransfer->getSumPriceToPayAggregation()
            ?? $expenseTransfer->getUnitPriceToPayAggregation()
        );

        return $expenseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $shipmentExpenseTransfer
     * @param int $price
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function sanitizeShipmentExpensePricesByPriceMode(ExpenseTransfer $shipmentExpenseTransfer, int $price, string $priceMode): ExpenseTransfer
    {
        if ($priceMode === $this->priceFacade->getNetPriceModeIdentifier()) {
            return $this->sanitizeShipmentExpensePricesForNetPriceMode($shipmentExpenseTransfer, $price);
        }

        return $this->sanitizeShipmentExpensePricesForGrossPriceMode($shipmentExpenseTransfer, $price);
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $shipmentExpenseTransfer
     * @param int $price
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function sanitizeShipmentExpensePricesForNetPriceMode(ExpenseTransfer $shipmentExpenseTransfer, int $price): ExpenseTransfer
    {
        return $shipmentExpenseTransfer->setUnitNetPrice($price)
            ->setUnitGrossPrice(0)
            ->setSumGrossPrice(0);
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $shipmentExpenseTransfer
     * @param int $price
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function sanitizeShipmentExpensePricesForGrossPriceMode(ExpenseTransfer $shipmentExpenseTransfer, int $price): ExpenseTransfer
    {
        return $shipmentExpenseTransfer->setUnitGrossPrice($price)
            ->setUnitNetPrice(0)
            ->setSumNetPrice(0);
    }
}
