<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Shipment;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupResponseTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\Shipment\Business\Checkout\ShipmentOrderSaverInterface;
use Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentMethodExtenderInterface;

class ShipmentSaver implements ShipmentSaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Shipment\Business\Checkout\ShipmentOrderSaverWithMultiShippingAddressInterface
     */
    protected $shipmentOrderSaver;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentMethodExtenderInterface
     */
    protected $shipmentMethodExtender;

    /**
     * @param \Spryker\Zed\Shipment\Business\Checkout\ShipmentOrderSaverInterface $shipmentOrderSaver
     * @param \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentMethodExtenderInterface $shipmentMethodExtender
     */
    public function __construct(ShipmentOrderSaverInterface $shipmentOrderSaver, ShipmentMethodExtenderInterface $shipmentMethodExtender)
    {
        $this->shipmentOrderSaver = $shipmentOrderSaver;
        $this->shipmentMethodExtender = $shipmentMethodExtender;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupResponseTransfer
     */
    public function saveShipment(ShipmentGroupTransfer $shipmentGroupTransfer, OrderTransfer $orderTransfer): ShipmentGroupResponseTransfer
    {
        $saveOrderTransfer = $this->buildSaveOrderTransfer($orderTransfer);
        $this->updateShipmentMethodForShipmentGroup($shipmentGroupTransfer, $orderTransfer);
        $expanse = $this->createShippingExpenseTransfer($shipmentGroupTransfer->getShipment()->getMethod(), $orderTransfer);
        $shipmentGroupTransfer->getShipment()->setExpense($expanse);

        $shipmentGroupTransfer = $this->handleDatabaseTransaction(function () use ($orderTransfer, $shipmentGroupTransfer, $saveOrderTransfer) {
            return $this->shipmentOrderSaver
                ->processShipmentGroup($orderTransfer, $shipmentGroupTransfer, $saveOrderTransfer);
        });

        $shipmentGroupResponseTransfer = new ShipmentGroupResponseTransfer();
        $shipmentGroupResponseTransfer->setIsSuccessful(true);
        $shipmentGroupResponseTransfer->setShipmentGroup($shipmentGroupTransfer);

        return $shipmentGroupResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function buildSaveOrderTransfer(OrderTransfer $orderTransfer): SaveOrderTransfer
    {
        return (new SaveOrderTransfer())
            ->setOrderItems($orderTransfer->getItems())
            ->setIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->setOrderReference($orderTransfer->getOrderReference())
            ->setOrderExpenses($orderTransfer->getExpenses());
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createShippingExpenseTransfer(ShipmentMethodTransfer $shipmentMethodTransfer, OrderTransfer $orderTransfer): ExpenseTransfer
    {
        $shipmentExpenseTransfer = new ExpenseTransfer();

        $shipmentExpenseTransfer->fromArray($shipmentMethodTransfer->toArray(), true);
        $shipmentExpenseTransfer->setFkSalesOrder($orderTransfer->getIdSalesOrder());
        $shipmentExpenseTransfer->setType(ShipmentConstants::SHIPMENT_EXPENSE_TYPE);
        $this->setPrice(
            $shipmentExpenseTransfer,
            $shipmentMethodTransfer->getStoreCurrencyPrice(),
            $orderTransfer->getPriceMode()
        );
        $shipmentExpenseTransfer->setQuantity(1);

        $shipmentExpenseTransfer = $this->sanitizeExpenseSumPrices($shipmentExpenseTransfer);

        return $shipmentExpenseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $shipmentExpenseTransfer
     * @param int $price
     * @param string $priceMode
     *
     * @return void
     */
    protected function setPrice(ExpenseTransfer $shipmentExpenseTransfer, int $price, string $priceMode): void
    {
        if ($priceMode === ShipmentConstants::PRICE_MODE_NET) {
            $shipmentExpenseTransfer->setUnitGrossPrice(0);
            $shipmentExpenseTransfer->setUnitPriceToPayAggregation(0);
            $shipmentExpenseTransfer->setUnitPrice($price);
            $shipmentExpenseTransfer->setUnitNetPrice($price);
            return;
        }

        $shipmentExpenseTransfer->setUnitPriceToPayAggregation(0);
        $shipmentExpenseTransfer->setUnitNetPrice(0);
        $shipmentExpenseTransfer->setUnitPrice($price);
        $shipmentExpenseTransfer->setUnitGrossPrice($price);
    }

    /**
     * @deprecated For BC reasons the missing sum prices are mirrored from unit prices. Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function sanitizeExpenseSumPrices(ExpenseTransfer $expenseTransfer): ExpenseTransfer
    {
        $expenseTransfer->setSumGrossPrice($expenseTransfer->getSumGrossPrice() ?? $expenseTransfer->getUnitGrossPrice());
        $expenseTransfer->setSumNetPrice($expenseTransfer->getSumNetPrice() ?? $expenseTransfer->getUnitNetPrice());
        $expenseTransfer->setSumPrice($expenseTransfer->getSumPrice() ?? $expenseTransfer->getUnitPrice());
        $expenseTransfer->setSumTaxAmount($expenseTransfer->getSumTaxAmount() ?? $expenseTransfer->getUnitTaxAmount());
        $expenseTransfer->setSumDiscountAmountAggregation($expenseTransfer->getSumDiscountAmountAggregation() ?? $expenseTransfer->getUnitDiscountAmountAggregation());
        $expenseTransfer->setSumPriceToPayAggregation($expenseTransfer->getSumPriceToPayAggregation() ?? $expenseTransfer->getUnitPriceToPayAggregation());

        return $expenseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function updateShipmentMethodForShipmentGroup(ShipmentGroupTransfer $shipmentGroupTransfer, OrderTransfer $orderTransfer): void
    {
        $shipmentMethodTransfer = $shipmentGroupTransfer->getShipment()->getMethod();
        $shipmentGroupTransfer
            ->getShipment()
            ->setMethod($this->shipmentMethodExtender->extendShipmentMethodTransfer($shipmentMethodTransfer, $orderTransfer));
    }
}
