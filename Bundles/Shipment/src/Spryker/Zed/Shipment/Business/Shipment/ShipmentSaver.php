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
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Zed\Shipment\Business\Checkout\MultiShipmentOrderSaverInterface;
use Spryker\Zed\Shipment\Business\ShipmentExpense\ShipmentExpenseCreatorInterface;
use Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentMethodExpanderInterface;

class ShipmentSaver implements ShipmentSaverInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Business\Checkout\MultiShipmentOrderSaverInterface
     */
    protected $shipmentOrderSaver;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentMethodExpanderInterface
     */
    protected $shipmentMethodExpander;

    /**
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @var \Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface
     */
    protected $shipmentMapper;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentExpense\ShipmentExpenseCreatorInterface
     */
    protected $shipmentExpenseCreator;

    /**
     * @param \Spryker\Zed\Shipment\Business\Checkout\MultiShipmentOrderSaverInterface $shipmentOrderSaver
     * @param \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentMethodExpanderInterface $shipmentMethodExpander
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     * @param \Spryker\Zed\Shipment\Business\ShipmentExpense\ShipmentExpenseCreatorInterface $shipmentExpenseCreator
     */
    public function __construct(
        MultiShipmentOrderSaverInterface $shipmentOrderSaver,
        ShipmentMethodExpanderInterface $shipmentMethodExpander,
        ShipmentServiceInterface $shipmentService,
        ShipmentExpenseCreatorInterface $shipmentExpenseCreator
    ) {
        $this->shipmentOrderSaver = $shipmentOrderSaver;
        $this->shipmentMethodExpander = $shipmentMethodExpander;
        $this->shipmentService = $shipmentService;
        $this->shipmentExpenseCreator = $shipmentExpenseCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupResponseTransfer
     */
    public function saveShipment(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        OrderTransfer $orderTransfer
    ): ShipmentGroupResponseTransfer {
        $shipmentGroupResponseTransfer = (new ShipmentGroupResponseTransfer())->setIsSuccessful(false);
        $shipmentTransfer = $shipmentGroupTransfer->requireShipment()->getShipment();

        if (!$this->isOrderShipmentUnique($shipmentTransfer, $orderTransfer)) {
            return $shipmentGroupResponseTransfer;
        }

        $saveOrderTransfer = $this->buildSaveOrderTransfer($orderTransfer);
        $shipmentGroupTransfer = $this->setShipmentMethod($shipmentGroupTransfer, $orderTransfer);
        $expenseTransfer = $this->getShippingExpenseTransfer($shipmentTransfer, $orderTransfer);
        $expenseTransfer->setName($shipmentTransfer->requireMethod()->getMethod()->getName());
        if ($expenseTransfer->getIdSalesExpense() === null) {
            $orderTransfer->addExpense($expenseTransfer);
        }

        $shipmentGroupTransfer = $this->shipmentOrderSaver
            ->saveOrderShipmentByShipmentGroup($orderTransfer, $shipmentGroupTransfer, $saveOrderTransfer);

        return $shipmentGroupResponseTransfer
            ->setIsSuccessful(true)
            ->setShipmentGroup($shipmentGroupTransfer);
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
    protected function getShippingExpenseTransfer(
        ShipmentTransfer $shipmentTransfer,
        OrderTransfer $orderTransfer
    ): ExpenseTransfer {
        $idShipmentTransfer = $shipmentTransfer->getIdSalesShipment();
        if ($idShipmentTransfer === null) {
            return $this->shipmentExpenseCreator->createShippingExpenseTransfer($shipmentTransfer, $orderTransfer);
        }

        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            $expenseShipmentTransfer = $expenseTransfer->getShipment();
            if ($expenseShipmentTransfer === null) {
                continue;
            }

            if ($expenseShipmentTransfer->getIdSalesShipment() === $idShipmentTransfer) {
                $expenseTransfer->setShipment($shipmentTransfer);

                return $expenseTransfer;
            }
        }

        return $this->shipmentExpenseCreator->createShippingExpenseTransfer($shipmentTransfer, $orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function setShipmentMethod(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        OrderTransfer $orderTransfer
    ): ShipmentGroupTransfer {
        $shipmentTransfer = $shipmentGroupTransfer->requireShipment()->getShipment();
        $shipmentMethodTransfer = $shipmentTransfer->requireMethod()->getMethod();

        $shipmentTransfer->setMethod($this->shipmentMethodExpander->expand($shipmentMethodTransfer, $orderTransfer));

        return $shipmentGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function isOrderShipmentUnique(ShipmentTransfer $shipmentTransfer, OrderTransfer $orderTransfer): bool
    {
        $itemTransfers = $orderTransfer->requireItems()->getItems();
        $orderShipmentGroupTransfers = $this->shipmentService->groupItemsByShipment($itemTransfers);
        if ($orderShipmentGroupTransfers->count() === 0) {
            return true;
        }

        $shipmentHasKey = $this->shipmentService->getShipmentHashKey($shipmentTransfer);
        $originalIdSalesShipment = $shipmentTransfer->getIdSalesShipment();
        foreach ($orderShipmentGroupTransfers as $orderShipmentGroupTransfer) {
            $idSalesShipment = $orderShipmentGroupTransfer->requireShipment()->getShipment()->getIdSalesShipment();
            if ($orderShipmentGroupTransfer->getHash() === $shipmentHasKey && $originalIdSalesShipment !== $idSalesShipment) {
                return false;
            }
        }

        return true;
    }
}
