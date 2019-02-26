<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Shipment;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupResponseTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\Shipment\Business\Checkout\MultiShipmentOrderSaverInterface;
use Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentMethodExtenderInterface;

class ShipmentSaver implements ShipmentSaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Shipment\Business\Checkout\MultiShipmentOrderSaverInterface
     */
    protected $shipmentOrderSaver;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentMethodExtenderInterface
     */
    protected $shipmentMethodExtender;

    /**
     * @param \Spryker\Zed\Shipment\Business\Checkout\MultiShipmentOrderSaverInterface $shipmentOrderSaver
     * @param \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentMethodExtenderInterface $shipmentMethodExtender
     */
    public function __construct(MultiShipmentOrderSaverInterface $shipmentOrderSaver, ShipmentMethodExtenderInterface $shipmentMethodExtender)
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
        $shipmentGroupTransfer = $this->updateShipmentMethodForShipmentGroup($shipmentGroupTransfer, $orderTransfer);
        $expenseTransfer = $this->createShippingExpenseTransfer($shipmentGroupTransfer->getShipment(), $orderTransfer);
        $orderTransfer = $this->addShippingExpenseToOrderExpenses($expenseTransfer, $orderTransfer);

        $shipmentGroupTransfer = $this->shipmentOrderSaver
            ->saveOrderShipmentByShipmentGroup($orderTransfer, $shipmentGroupTransfer, $saveOrderTransfer);

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
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createShippingExpenseTransfer(ShipmentTransfer $shipmentTransfer, OrderTransfer $orderTransfer): ExpenseTransfer
    {
        $shipmentMethodTransfer = $shipmentTransfer->getMethod();
        $expenseTransfer = new ExpenseTransfer();

        $expenseTransfer->fromArray($shipmentMethodTransfer->modifiedToArray(), true);
        $expenseTransfer->setFkSalesOrder($orderTransfer->getIdSalesOrder());
        $expenseTransfer->setType(ShipmentConstants::SHIPMENT_EXPENSE_TYPE);
        $this->setPrice(
            $expenseTransfer,
            $shipmentMethodTransfer->getStoreCurrencyPrice(),
            $orderTransfer->getPriceMode()
        );
        $expenseTransfer->setQuantity(1);

        $expenseTransfer = $this->sanitizeExpenseSumPrices($expenseTransfer);

        $expenseTransfer->setShipment($shipmentTransfer);

        return $expenseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function addShippingExpenseToOrderExpenses(ExpenseTransfer $expenseTransfer, OrderTransfer $orderTransfer): OrderTransfer
    {
        $orderTransfer = $this->removeExistingShippingExpenseFromOrderExpenses($expenseTransfer, $orderTransfer);
        $orderTransfer->addExpense($expenseTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function removeExistingShippingExpenseFromOrderExpenses(ExpenseTransfer $expenseTransfer, OrderTransfer $orderTransfer): OrderTransfer
    {
        $orderExpensesCollection = new ArrayObject();

        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getShipment() === $expenseTransfer->getShipment()) {
                continue;
            }

            $orderExpensesCollection->append($expenseTransfer);
        }

        $orderTransfer->setExpenses($orderExpensesCollection);

        return $orderTransfer;
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
     * @deprecated For BC reasons the missing sum prices are mirrored from unit prices. Exists for Backward Compatibility reasons only.
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
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function updateShipmentMethodForShipmentGroup(ShipmentGroupTransfer $shipmentGroupTransfer, OrderTransfer $orderTransfer): ShipmentGroupTransfer
    {
        $shipmentMethodTransfer = $shipmentGroupTransfer->getShipment()->getMethod();
        $shipmentGroupTransfer
            ->getShipment()
            ->setMethod($this->shipmentMethodExtender->extendShipmentMethodTransfer($shipmentMethodTransfer, $orderTransfer));

        return $shipmentGroupTransfer;
    }
}
