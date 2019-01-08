<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Checkout;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface;

class ShipmentOrderSaver implements ShipmentOrderSaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesInterface $salesFacade
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     */
    public function __construct(
        ShipmentEntityManagerInterface $entityManager,
        ShipmentToSalesInterface $salesFacade,
        ShipmentServiceInterface $shipmentService
    ) {
        $this->entityManager = $entityManager;
        $this->salesFacade = $salesFacade;
        $this->shipmentService = $shipmentService;
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
        $salesOrderTransfer = $this->salesFacade->getOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        $quoteTransfer->setShipmentGroups(
            $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems())
        );

        foreach ($quoteTransfer->getShipmentGroups() as $shipmentGroupTransfer) {
            $this->saveShipmentAddressTransfer($shipmentGroupTransfer);
            $this->addExpensesToOrder($shipmentGroupTransfer, $salesOrderTransfer, $saveOrderTransfer);
            $idSalesShipment = $this->entityManager->createSalesShipment(
                $shipmentGroupTransfer->getShipment(),
                $salesOrderTransfer->getIdSalesOrder()
            );
            $this->updateItemsShipment($shipmentGroupTransfer, $idSalesShipment);
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
        $expenseTransfer = $shipmentGroupTransfer->getShipment()->getExpense();
        $expenseTransfer->setFkSalesOrder($salesOrderTransfer->getIdSalesOrder());
        $expenseTransfer = $this->salesFacade->createSalesExpense($expenseTransfer);

        $shipmentGroupTransfer
            ->getShipment()
            ->getExpense()
            ->setIdSalesExpense($expenseTransfer->getIdSalesExpense());

        $salesOrderTransfer->addExpense($expenseTransfer);
        $saveOrderTransfer->addOrderExpense($expenseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param int $idSalesShipment
     *
     * @return void
     */
    protected function updateItemsShipment(
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
        }
    }
}
