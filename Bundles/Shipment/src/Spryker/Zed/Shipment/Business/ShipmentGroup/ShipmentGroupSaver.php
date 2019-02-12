<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentGroup;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface;

class ShipmentGroupSaver implements ShipmentGroupSaverInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentMethodExtenderInterface
     */
    protected $shipmentMethodExtender;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentMethodExtenderInterface $shipmentMethodExtender
     */
    public function __construct(
        ShipmentEntityManagerInterface $entityManager,
        ShipmentToSalesFacadeInterface $salesFacade,
        ShipmentMethodExtenderInterface $shipmentMethodExtender
    ) {
        $this->entityManager = $entityManager;
        $this->salesFacade = $salesFacade;
        $this->shipmentMethodExtender = $shipmentMethodExtender;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $salesOrderTransfer
     *
     * @return void
     */
    public function saveShipmentGroup(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        OrderTransfer $salesOrderTransfer
    ): void {
        $this->createSalesOrderAddress($shipmentGroupTransfer);
        $this->updateShipmentMethodForShipmentGroup($shipmentGroupTransfer, $salesOrderTransfer);
        $this->createSalesExpense($shipmentGroupTransfer, $salesOrderTransfer);

        $idSalesShipment = $this->entityManager->createSalesShipment(
            $shipmentGroupTransfer->getShipment(),
            $salesOrderTransfer->getIdSalesOrder()
        );

        $this->updateSalesOrderItems($shipmentGroupTransfer, $idSalesShipment);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return void
     */
    protected function createSalesOrderAddress(ShipmentGroupTransfer $shipmentGroupTransfer): void
    {
        $addressTransfer = $shipmentGroupTransfer->getShipment()->getShippingAddress();
        $this->salesFacade->createOrderAddress($addressTransfer);
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

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function createSalesExpense(ShipmentGroupTransfer $shipmentGroupTransfer, OrderTransfer $orderTransfer): void
    {
        $expenseTransfer = $this->createShippingExpenseTransfer(
            $shipmentGroupTransfer->getShipment()->getMethod(),
            $orderTransfer
        );

        $this->salesFacade->createSalesExpense($expenseTransfer);
        $shipmentGroupTransfer->getShipment()->setExpense($expenseTransfer);
        $orderTransfer->addExpense($expenseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param int $idSalesShipment
     *
     * @return void
     */
    protected function updateSalesOrderItems(ShipmentGroupTransfer $shipmentGroupTransfer, int $idSalesShipment): void
    {
        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            $this->entityManager->updateSalesOrderItemFkShipment($itemTransfer, $idSalesShipment);
        }
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
}
