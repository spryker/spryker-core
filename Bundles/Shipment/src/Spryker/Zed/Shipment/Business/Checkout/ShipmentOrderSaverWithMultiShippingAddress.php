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
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCustomerInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface;

class ShipmentOrderSaverWithMultiShippingAddress implements ShipmentOrderSaverWithMultiShippingAddressInterface
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
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCustomerInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @deprecated Will be removed in next major release.
     *
     * @var \Spryker\Zed\Shipment\Business\Checkout\QuoteDataBCForMultiShipmentAdapterInterface
     */
    protected $quoteDataBCForMultiShipmentAdapter;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCustomerInterface $customerFacade
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     * @param \Spryker\Zed\Shipment\Business\Checkout\QuoteDataBCForMultiShipmentAdapterInterface $quoteDataBCForMultiShipmentAdapter
     */
    public function __construct(
        ShipmentEntityManagerInterface $entityManager,
        ShipmentToSalesFacadeInterface $salesFacade,
        ShipmentToCustomerInterface $customerFacade,
        ShipmentServiceInterface $shipmentService,
        QuoteDataBCForMultiShipmentAdapterInterface $quoteDataBCForMultiShipmentAdapter
    ) {
        $this->entityManager = $entityManager;
        $this->salesFacade = $salesFacade;
        $this->customerFacade = $customerFacade;
        $this->shipmentService = $shipmentService;
        $this->quoteDataBCForMultiShipmentAdapter = $quoteDataBCForMultiShipmentAdapter;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderShipment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        /**
         * @deprecated Will be removed in next major release.
         */
        $quoteTransfer = $this->quoteDataBCForMultiShipmentAdapter->adapt($quoteTransfer);

        $this->assertShipmentRequirements($quoteTransfer);

        $this->handleDatabaseTransaction(function () use ($quoteTransfer, $saveOrderTransfer) {
            $this->saveOrderShipmentTransaction($quoteTransfer, $saveOrderTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function processShipmentGroup(OrderTransfer $orderTransfer, ShipmentGroupTransfer $shipmentGroupTransfer, SaveOrderTransfer $saveOrderTransfer): ShipmentGroupTransfer
    {
        $this->saveShipmentAddressTransfer($shipmentGroupTransfer);
        $this->addExpensesToOrder($shipmentGroupTransfer, $orderTransfer, $saveOrderTransfer);

        $idSalesShipment = $this->entityManager->createSalesShipment(
            $shipmentGroupTransfer->getShipment(),
            $orderTransfer->getIdSalesOrder()
        );

        $this->updateSalesOrderItemsFkShipment($shipmentGroupTransfer, $idSalesShipment);

        return $shipmentGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function saveOrderShipmentTransaction(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $salesOrderTransfer = $this->salesFacade->getOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        $shipmentGroups = $this->shipmentService->groupItemsByShipment($saveOrderTransfer->getOrderItems());

        foreach ($shipmentGroups as $shipmentGroupTransfer) {
            $this->processShipmentGroup($salesOrderTransfer, $shipmentGroupTransfer, $saveOrderTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return void
     */
    protected function saveShipmentAddressTransfer(ShipmentGroupTransfer $shipmentGroupTransfer): void
    {
        $shippingAddressTransfer = $shipmentGroupTransfer->getShipment()->getShippingAddress();
        $customerAddressTransfer = $this->customerFacade->findCustomerAddressByAddressData($shippingAddressTransfer);
        if ($customerAddressTransfer !== null) {
            $shippingAddressTransfer = $customerAddressTransfer;
        }

        $shippingAddressTransfer = $this->salesFacade->createOrderAddress($shippingAddressTransfer);

        $shipmentGroupTransfer->getShipment()->setShippingAddress($shippingAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $salesOrderTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function addExpensesToOrder(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        OrderTransfer $salesOrderTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
        $expenseTransfer = $this->sanitizeExpenseSumPrices($shipmentGroupTransfer->getShipment()->getExpense());
        $expenseTransfer->setFkSalesOrder($salesOrderTransfer->getIdSalesOrder());
        $this->createExpense($expenseTransfer);

        $shipmentGroupTransfer
            ->getShipment()
            ->getExpense()
            ->setIdSalesExpense($expenseTransfer->getIdSalesExpense());

        $salesOrderTransfer->addExpense($expenseTransfer);
        $saveOrderTransfer->addOrderExpense($expenseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpense(ExpenseTransfer $expenseTransfer): ExpenseTransfer
    {
        if ($expenseTransfer->getIdSalesExpense()) {
            return $expenseTransfer;
        }

        return $this->salesFacade->createSalesExpense($expenseTransfer);
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
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param int $idSalesShipment
     *
     * @return void
     */
    protected function updateSalesOrderItemsFkShipment(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        int $idSalesShipment
    ): void {
        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            $this->entityManager->updateSalesOrderItemFkShipment($itemTransfer, $idSalesShipment);
        }
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
            $itemTransfer->getShipment()->requireShippingAddress();
            $itemTransfer->getShipment()->requireExpense();
        }
    }
}
