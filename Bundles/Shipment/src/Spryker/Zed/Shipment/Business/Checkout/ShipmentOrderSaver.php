<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Checkout;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Shipment\ShipmentConfig;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

/**
 * @deprecated Use \Spryker\Zed\Shipment\Business\Checkout\MultiShipmentOrderSaver instead.
 */
class ShipmentOrderSaver implements ShipmentOrderSaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface
     */
    protected $expenseSanitizer;

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface
     */
    protected $shipmentRepository;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface $expenseSanitizer
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     */
    public function __construct(
        ShipmentEntityManagerInterface $entityManager,
        ExpenseSanitizerInterface $expenseSanitizer,
        ShipmentRepositoryInterface $shipmentRepository
    ) {
        $this->entityManager = $entityManager;
        $this->expenseSanitizer = $expenseSanitizer;
        $this->shipmentRepository = $shipmentRepository;
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
    protected function saveOrderShipmentTransaction(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $salesOrderTransfer = $this->findSalesOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        if ($salesOrderTransfer === null) {
            return;
        }

        $salesOrderTransfer = $this->addExpensesToOrder($quoteTransfer, $salesOrderTransfer, $saveOrderTransfer);
        $shipmentTransfer = $this->createSalesShipment($quoteTransfer, $salesOrderTransfer, $saveOrderTransfer);

        if ($shipmentTransfer === null) {
            return;
        }

        $this->updateFkShipmentForOrderItems($saveOrderTransfer->getOrderItems(), $shipmentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertShipmentRequirements(QuoteTransfer $quoteTransfer): void
    {
        $quoteTransfer->requireShipment();
        $quoteTransfer->getShipment()->requireMethod();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function addExpensesToOrder(
        QuoteTransfer $quoteTransfer,
        OrderTransfer $orderTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): OrderTransfer {
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() !== ShipmentConfig::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $sanitizedExpenseTransfer = $this->expenseSanitizer->sanitizeExpenseSumValues(clone $expenseTransfer);
            $salesOrderExpenseTransfer = $this->entityManager->saveSalesExpense($sanitizedExpenseTransfer, $orderTransfer);
            $saveOrderTransfer = $this->setCheckoutResponseExpenses($saveOrderTransfer, $expenseTransfer, $salesOrderExpenseTransfer);

            $orderTransfer->addExpense($salesOrderExpenseTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    protected function findSalesOrderByIdSalesOrder($idSalesOrder): ?OrderTransfer
    {
        return $this->shipmentRepository->findSalesOrderById($idSalesOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $salesOrderExpenseTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function setCheckoutResponseExpenses(
        SaveOrderTransfer $saveOrderTransfer,
        ExpenseTransfer $expenseTransfer,
        ExpenseTransfer $salesOrderExpenseTransfer
    ): SaveOrderTransfer {
        $orderExpense = clone $expenseTransfer;
        $orderExpense->setIdSalesExpense($salesOrderExpenseTransfer->getIdSalesExpense());
        $saveOrderTransfer->addOrderExpense($orderExpense);

        return $saveOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $salesOrderTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    protected function createSalesShipment(
        QuoteTransfer $quoteTransfer,
        OrderTransfer $salesOrderTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): ?ShipmentTransfer {
        $shipmentTransfer = $quoteTransfer->getShipment();
        if ($shipmentTransfer === null) {
            return null;
        }

        $shipmentMethodTransfer = $shipmentTransfer->getMethod();
        if ($shipmentMethodTransfer === null) {
            return null;
        }

        $shipmentMethodName = $shipmentMethodTransfer->getName();
        if ($shipmentMethodName === null || $shipmentMethodName === '') {
            return null;
        }

        $salesShipmentExpense = $this->findShipmentExpense($saveOrderTransfer, $shipmentMethodName);

        return $this->entityManager->saveSalesShipment($shipmentTransfer, $salesOrderTransfer, $salesShipmentExpense);
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param string $shipmentMethodName
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer|null
     */
    protected function findShipmentExpense(SaveOrderTransfer $saveOrderTransfer, $shipmentMethodName): ?ExpenseTransfer
    {
        foreach ($saveOrderTransfer->getOrderExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() === ShipmentConfig::SHIPMENT_EXPENSE_TYPE && $shipmentMethodName === $expenseTransfer->getName()) {
                return $expenseTransfer;
            }
        }

        return null;
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return void
     */
    protected function updateFkShipmentForOrderItems(iterable $itemTransfers, ShipmentTransfer $shipmentTransfer): void
    {
        foreach ($itemTransfers as $itemTransfer) {
            $this->entityManager->updateFkShipmentForOrderItem($itemTransfer, $shipmentTransfer);
        }
    }
}
