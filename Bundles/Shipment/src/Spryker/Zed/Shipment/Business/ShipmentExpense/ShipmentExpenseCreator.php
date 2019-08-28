<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentExpense;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface;
use Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface;

class ShipmentExpenseCreator implements ShipmentExpenseCreatorInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface
     */
    protected $expenseSanitizer;

    /**
     * @var \Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface
     */
    protected $shipmentMapper;

    /**
     * @param \Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface $shipmentMapper
     * @param \Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface $expenseSanitizer
     */
    public function __construct(
        ShipmentMapperInterface $shipmentMapper,
        ExpenseSanitizerInterface $expenseSanitizer
    ) {
        $this->expenseSanitizer = $expenseSanitizer;
        $this->shipmentMapper = $shipmentMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function createShippingExpenseTransfer(
        ShipmentTransfer $shipmentTransfer,
        OrderTransfer $orderTransfer
    ): ExpenseTransfer {
        $shipmentMethodTransfer = $shipmentTransfer->requireMethod()->getMethod();

        $expenseTransfer = $this->shipmentMapper
            ->mapShipmentMethodTransferToExpenseTransfer($shipmentMethodTransfer, new ExpenseTransfer());

        $expenseTransfer->setFkSalesOrder($orderTransfer->getIdSalesOrder());
        $expenseTransfer->setType(ShipmentConstants::SHIPMENT_EXPENSE_TYPE);
        $expenseTransfer = $this->setExpenseSetPrice($expenseTransfer, 0, $orderTransfer->getPriceMode());
        $expenseTransfer->setQuantity(1);
        $expenseTransfer->setShipment($shipmentTransfer);

        return $this->expenseSanitizer->sanitizeExpenseSumValues($expenseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $shipmentExpenseTransfer
     * @param int $price
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function setExpenseSetPrice(
        ExpenseTransfer $shipmentExpenseTransfer,
        int $price,
        string $priceMode
    ): ExpenseTransfer {
        if ($priceMode === ShipmentConstants::PRICE_MODE_NET) {
            $shipmentExpenseTransfer->setUnitGrossPrice(0)
                ->setUnitPriceToPayAggregation(0)
                ->setUnitPrice($price)
                ->setUnitNetPrice($price);

            return $shipmentExpenseTransfer;
        }

        $shipmentExpenseTransfer->setUnitPriceToPayAggregation(0)
            ->setUnitNetPrice(0)
            ->setUnitPrice($price)
            ->setUnitGrossPrice($price);

        return $shipmentExpenseTransfer;
    }
}
