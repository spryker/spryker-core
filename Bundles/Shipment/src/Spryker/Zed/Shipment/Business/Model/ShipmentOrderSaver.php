<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
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

        $this->addShippingDetailsToOrder($quoteTransfer, $salesOrderEntity);
        $this->addExpensesToOrder($quoteTransfer, $salesOrderEntity, $checkoutResponse);

        $salesOrderEntity->save();
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
     * @throws \Propel\Runtime\Exception\PropelException
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
                $salesOrderExpenseEntity->save();

                $this->setCheckoutResponseExpenses($checkoutResponseTransfer, $expenseTransfer, $salesOrderExpenseEntity);

                $salesOrderEntity->addExpense($salesOrderExpenseEntity);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    protected function addShippingDetailsToOrder(QuoteTransfer $quoteTransfer, SpySalesOrder $salesOrderEntity)
    {
        $shipmentMethodTransfer = $quoteTransfer->getShipment()->getMethod();
        $salesOrderEntity->setFkShipmentMethod($shipmentMethodTransfer->getIdShipmentMethod());
        $salesOrderEntity->setShipmentDeliveryTime($shipmentMethodTransfer->getDeliveryTime());
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

}
