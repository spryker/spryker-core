<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Checkout;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class ShipmentOrderSaver implements ShipmentOrderSaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     */
    public function __construct(SalesQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderShipment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $this->assertShipmentRequirements($quoteTransfer);

        $this->handleDatabaseTransaction(function () use ($quoteTransfer, $saveOrderTransfer) {
            $this->saveOrderShipmentTransaction($quoteTransfer, $saveOrderTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function saveOrderShipmentTransaction(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $salesOrderEntity = $this->getSalesOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        $this->addExpensesToOrder($quoteTransfer, $salesOrderEntity, $saveOrderTransfer);
        $this->createSalesShipment($quoteTransfer, $salesOrderEntity, $saveOrderTransfer);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense $salesOrderExpenseEntity
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return void
     */
    protected function hydrateOrderExpenseEntity(
        SpySalesExpense $salesOrderExpenseEntity,
        ExpenseTransfer $expenseTransfer
    ) {
        $sanitizedExpenseTransfer = $this->sanitizeExpenseSumPrices(clone $expenseTransfer);

        $salesOrderExpenseEntity->fromArray($expenseTransfer->toArray());
        $salesOrderExpenseEntity->setGrossPrice($sanitizedExpenseTransfer->getSumGrossPrice());
        $salesOrderExpenseEntity->setNetPrice($sanitizedExpenseTransfer->getSumNetPrice());
        $salesOrderExpenseEntity->setPrice($sanitizedExpenseTransfer->getSumPrice());
        $salesOrderExpenseEntity->setTaxAmount($sanitizedExpenseTransfer->getSumTaxAmount());
        $salesOrderExpenseEntity->setDiscountAmountAggregation($sanitizedExpenseTransfer->getSumDiscountAmountAggregation());
        $salesOrderExpenseEntity->setPriceToPayAggregation($sanitizedExpenseTransfer->getSumPriceToPayAggregation());
    }

    /**
     * @deprecated For BC reasons the missing sum prices are mirrored from unit prices
     *
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function sanitizeExpenseSumPrices(ExpenseTransfer $expenseTransfer)
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertShipmentRequirements(QuoteTransfer $quoteTransfer): void
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->requireShipment();
            $itemTransfer->getShipment()->requireMethod();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function addExpensesToOrder(
        QuoteTransfer $quoteTransfer,
        SpySalesOrder $salesOrderEntity,
        SaveOrderTransfer $saveOrderTransfer
    ) {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $expenseTransfer = $itemTransfer->getShipment()->getExpense();
            $salesOrderItem = $this->findSalesOrderItem($salesOrderEntity, $itemTransfer->getIdSalesOrderItem());
            if ($salesOrderItem === null) {
                continue;
            }

            $salesOrderExpenseEntity = $this->createSpySalesExpense($expenseTransfer, $salesOrderEntity->getIdSalesOrder());
            $this->setCheckoutResponseExpenses($saveOrderTransfer, $expenseTransfer, $salesOrderExpenseEntity);
            $salesOrderItem->getSpySalesShipment()->setExpense($salesOrderExpenseEntity);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense
     */
    protected function createSpySalesExpense(ExpenseTransfer $expenseTransfer, int $idSalesOrder): SpySalesExpense
    {
        $salesOrderExpenseEntity = new SpySalesExpense();
        $this->hydrateOrderExpenseEntity($salesOrderExpenseEntity, $expenseTransfer);
        $salesOrderExpenseEntity->setFkSalesOrder($idSalesOrder);
        $salesOrderExpenseEntity->save();

        return $salesOrderExpenseEntity;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     * @param int $salesOrderItemId
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem|null
     */
    protected function findSalesOrderItem(SpySalesOrder $order, int $salesOrderItemId): ?SpySalesOrderItem
    {
        foreach ($order->getItems() as $salesOrderItem) {
            if ($salesOrderItem->getIdSalesOrderItem() === $salesOrderItemId) {
                return $salesOrderItem;
            }
        }

        return null;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder|null
     */
    protected function getSalesOrderByIdSalesOrder($idSalesOrder)
    {
        return $this->queryContainer->querySalesOrderById($idSalesOrder)->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense $salesOrderExpenseEntity
     *
     * @return void
     */
    protected function setCheckoutResponseExpenses(
        SaveOrderTransfer $saveOrderTransfer,
        ExpenseTransfer $expenseTransfer,
        SpySalesExpense $salesOrderExpenseEntity
    ) {
        $orderExpense = clone $expenseTransfer;
        $orderExpense->setIdSalesExpense($salesOrderExpenseEntity->getIdSalesExpense());
        $saveOrderTransfer->addOrderExpense($orderExpense);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function createSalesShipment(
        QuoteTransfer $quoteTransfer,
        SpySalesOrder $salesOrderEntity,
        SaveOrderTransfer $saveOrderTransfer
    ) {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shipmentMethodTransfer = $itemTransfer->getShipment()->getMethod();
            $idSalesExpense = $this->findShipmentExpenseId(
                $saveOrderTransfer,
                $shipmentMethodTransfer->getName()
            );
            if ($idSalesExpense === null) {
                continue;
            }

            $this->createSalesShipmentEntity($salesOrderEntity, $shipmentMethodTransfer, $idSalesExpense);
        }
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param int $idSalesExpense
     *
     * @return void
     */
    protected function createSalesShipmentEntity(
        SpySalesOrder $salesOrderEntity,
        ShipmentMethodTransfer $shipmentMethodTransfer,
        $idSalesExpense
    ): void {
        $salesShipmentEntity = $this->mapSalesShipmentEntity($salesOrderEntity, $shipmentMethodTransfer, $idSalesExpense);
        $salesShipmentEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param string $methodName
     *
     * @return int|null
     */
    protected function findShipmentExpenseId(SaveOrderTransfer $saveOrderTransfer, $methodName)
    {
        foreach ($saveOrderTransfer->getOrderExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() === ShipmentConstants::SHIPMENT_EXPENSE_TYPE && $methodName === $expenseTransfer->getName()) {
                return $expenseTransfer->getIdSalesExpense();
            }
        }

        return null;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param int $idSalesExpense
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipment
     */
    protected function mapSalesShipmentEntity(
        SpySalesOrder $salesOrderEntity,
        ShipmentMethodTransfer $shipmentMethodTransfer,
        $idSalesExpense
    ) {
        $salesShipmentEntity = new SpySalesShipment();
        $salesShipmentEntity->fromArray($shipmentMethodTransfer->toArray());
        $salesShipmentEntity->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
        $salesShipmentEntity->setFkSalesExpense($idSalesExpense);

        // todo: Split Delivery. Get rid from it, after old logic will be removed
        if (!$salesShipmentEntity->getFkSalesOrderAddress()) {
            $salesShipmentEntity->setFkSalesOrderAddress($this->getMockSAlesOrderAddressId());
        }

        return $salesShipmentEntity;
    }

    /**
     * Split Delivery. Get rid from it, after old logic will be removed
     *
     * @return int
     */
    protected function getMockSAlesOrderAddressId(): int
    {
        $country = SpyCountryQuery::create()->findOneByIso2Code('DE');

        $billingAddress = (new SpySalesOrderAddress())
            ->setFkCountry($country->getIdCountry())
            ->setFirstName('Serhii')
            ->setLastName('Spryker')
            ->setAddress1('Straße des 17. Juni 135')
            ->setCity('Berlin')
            ->setZipCode('10623');
        $billingAddress->save();

        return $billingAddress->getIdSalesOrderAddress();
    }
}
