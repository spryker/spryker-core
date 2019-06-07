<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Checkout;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface;
use Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCustomerInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface;

/**
 * @deprecated Use \Spryker\Zed\Shipment\Business\Checkout\MultiShipmentOrderSaver instead.
 */
class ShipmentOrderSaver implements ShipmentOrderSaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCustomerInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface
     */
    protected $expenseSanitizer;

    /**
     * @var \Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface
     */
    protected $shipmentMapper;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCustomerInterface $customerFacade
     * @param \Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface $expenseSanitizer
     * @param \Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface $shipmentMapper
     */
    public function __construct(
        SalesQueryContainerInterface $queryContainer,
        ShipmentEntityManagerInterface $entityManager,
        ShipmentToSalesFacadeInterface $salesFacade,
        ShipmentToCustomerInterface $customerFacade,
        ExpenseSanitizerInterface $expenseSanitizer,
        ShipmentMapperInterface $shipmentMapper
    ) {
        $this->salesQueryContainer = $queryContainer;
        $this->entityManager = $entityManager;
        $this->salesFacade = $salesFacade;
        $this->customerFacade = $customerFacade;
        $this->expenseSanitizer = $expenseSanitizer;
        $this->shipmentMapper = $shipmentMapper;
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
        $salesOrderEntity = $this->findSalesOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        if ($salesOrderEntity === null) {
            return;
        }

        $salesOrderEntity = $this->addExpensesToOrder($quoteTransfer, $salesOrderEntity, $saveOrderTransfer);
        $shipmentTransfer = $this->createSalesShipment($quoteTransfer, $salesOrderEntity, $saveOrderTransfer);
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
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function addExpensesToOrder(
        QuoteTransfer $quoteTransfer,
        SpySalesOrder $salesOrderEntity,
        SaveOrderTransfer $saveOrderTransfer
    ): SpySalesOrder {
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() !== ShipmentConstants::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $sanitizedExpenseTransfer = $this->expenseSanitizer->sanitizeExpenseSumValues(clone $expenseTransfer);
            $salesOrderExpenseEntity = $this->shipmentMapper
                ->mapOrderSalesExpenseEntityToExpenseTransfer($sanitizedExpenseTransfer, new SpySalesExpense());

            $salesOrderExpenseEntity->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
            $salesOrderExpenseEntity->save();

            $this->setCheckoutResponseExpenses($saveOrderTransfer, $expenseTransfer, $salesOrderExpenseEntity);

            $salesOrderEntity->addExpense($salesOrderExpenseEntity);
        }

        return $salesOrderEntity;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder|null
     */
    protected function findSalesOrderByIdSalesOrder($idSalesOrder): ?SpySalesOrder
    {
        return $this->salesQueryContainer->querySalesOrderById($idSalesOrder)->findOne();
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
    ): void {
        $orderExpense = clone $expenseTransfer;
        $orderExpense->setIdSalesExpense($salesOrderExpenseEntity->getIdSalesExpense());
        $saveOrderTransfer->addOrderExpense($orderExpense);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function createSalesShipment(
        QuoteTransfer $quoteTransfer,
        SpySalesOrder $salesOrderEntity,
        SaveOrderTransfer $saveOrderTransfer
    ): ShipmentTransfer {
        $shipmentTransfer = $quoteTransfer->getShipment();
        $idSalesExpense = $this->findShipmentExpenseId($saveOrderTransfer, $shipmentTransfer->getMethod()->getName());

        $shipmentEntity = $this->prepareSalesShipmentEntity($salesOrderEntity, $shipmentTransfer, $idSalesExpense);
        $shipmentEntity->save();

        $shipmentTransfer->setIdSalesShipment($shipmentEntity->getIdSalesShipment());

        return $shipmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param string $methodName
     *
     * @return int|null
     */
    protected function findShipmentExpenseId(SaveOrderTransfer $saveOrderTransfer, $methodName): ?int
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
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param int|null $idSalesExpense
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipment
     */
    protected function prepareSalesShipmentEntity(
        SpySalesOrder $salesOrderEntity,
        ShipmentTransfer $shipmentTransfer,
        ?int $idSalesExpense
    ): SpySalesShipment {
        $shipmentEntity = new SpySalesShipment();
        $shipmentEntity = $this->shipmentMapper->mapShipmentMethodTransferToShipmentEntity($shipmentTransfer->getMethod(), $shipmentEntity);
        $shipmentEntity->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
        $shipmentEntity->setFkSalesExpense($idSalesExpense);
        $shipmentEntity->setFkSalesOrderAddress($salesOrderEntity->getShippingAddress()->getIdSalesOrderAddress());

        return $shipmentEntity;
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
