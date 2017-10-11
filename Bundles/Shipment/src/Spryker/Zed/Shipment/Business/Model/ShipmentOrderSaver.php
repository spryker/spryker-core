<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Propel\Runtime\Propel;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class ShipmentOrderSaver implements ShipmentOrderSaverInterface
{

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
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveShipmentForOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->assertShipmentRequirements($quoteTransfer);

        Propel::getConnection()->beginTransaction();

        $salesOrderEntity = $this->getSalesOrderByIdSalesOrder($checkoutResponse->getSaveOrder()->getIdSalesOrder());

        $this->addExpensesToOrder($quoteTransfer, $salesOrderEntity, $checkoutResponse);
        $this->createSalesShipment($quoteTransfer, $salesOrderEntity, $checkoutResponse);

        Propel::getConnection()->commit();
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
        $salesOrderExpenseEntity->fromArray($expenseTransfer->toArray());
        $salesOrderExpenseEntity->setGrossPrice($expenseTransfer->getUnitGrossPrice());
        $salesOrderExpenseEntity->setNetPrice($expenseTransfer->getUnitNetPrice());
        $salesOrderExpenseEntity->setPrice($expenseTransfer->getUnitPrice());
        $salesOrderExpenseEntity->setTaxAmount($expenseTransfer->getUnitTaxAmount());
        $salesOrderExpenseEntity->setDiscountAmountAggregation($expenseTransfer->getUnitDiscountAmountAggregation());
        $salesOrderExpenseEntity->setPriceToPayAggregation($expenseTransfer->getUnitPriceToPayAggregation());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertShipmentRequirements(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireShipment();
        $quoteTransfer->getShipment()->requireMethod();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function addExpensesToOrder(
        QuoteTransfer $quoteTransfer,
        SpySalesOrder $salesOrderEntity,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if (ShipmentConstants::SHIPMENT_EXPENSE_TYPE === $expenseTransfer->getType()) {
                $salesOrderExpenseEntity = new SpySalesExpense();
                $this->hydrateOrderExpenseEntity($salesOrderExpenseEntity, $expenseTransfer);
                $salesOrderExpenseEntity->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
                $salesOrderExpenseEntity->save();

                $this->setCheckoutResponseExpenses($checkoutResponseTransfer, $expenseTransfer, $salesOrderExpenseEntity);

                $salesOrderEntity->addExpense($salesOrderExpenseEntity);
            }
        }
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function getSalesOrderByIdSalesOrder($idSalesOrder)
    {
        return $this->queryContainer->querySalesOrderById($idSalesOrder)->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense $salesOrderExpenseEntity
     *
     * @return void
     */
    protected function setCheckoutResponseExpenses(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        ExpenseTransfer $expenseTransfer,
        SpySalesExpense $salesOrderExpenseEntity
    ) {
        $orderExpense = clone $expenseTransfer;
        $orderExpense->setIdSalesExpense($salesOrderExpenseEntity->getIdSalesExpense());
        $checkoutResponseTransfer->getSaveOrder()->addOrderExpense($orderExpense);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function createSalesShipment(
        QuoteTransfer $quoteTransfer,
        SpySalesOrder $salesOrderEntity,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {

        $shipmentMethodTransfer = $quoteTransfer->getShipment()->getMethod();
        $idSalesExpense = $this->findShipmentExpenseId(
            $checkoutResponseTransfer->getSaveOrder(),
            $shipmentMethodTransfer->getName()
        );

        if (!$idSalesExpense) {
            return;
        }

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

        return $salesShipmentEntity;
    }

}
