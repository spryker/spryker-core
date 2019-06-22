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
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Zed\Shipment\Business\Checkout\MultiShipmentOrderSaverInterface;
use Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface;
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
     * @var \Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface
     */
    protected $expenseSanitizer;

    /**
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \Spryker\Zed\Shipment\Business\Checkout\MultiShipmentOrderSaverInterface $shipmentOrderSaver
     * @param \Spryker\Zed\Shipment\Business\ShipmentGroup\ShipmentMethodExpanderInterface $shipmentMethodExpander
     * @param \Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface $expenseSanitizer
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     */
    public function __construct(
        MultiShipmentOrderSaverInterface $shipmentOrderSaver,
        ShipmentMethodExpanderInterface $shipmentMethodExpander,
        ExpenseSanitizerInterface $expenseSanitizer,
        ShipmentServiceInterface $shipmentService
    ) {
        $this->shipmentOrderSaver = $shipmentOrderSaver;
        $this->shipmentMethodExpander = $shipmentMethodExpander;
        $this->expenseSanitizer = $expenseSanitizer;
        $this->shipmentService = $shipmentService;
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
        if (!$this->isOrderShipmentUnique($shipmentGroupTransfer->requireShipment()->getShipment(), $orderTransfer)) {
            return $shipmentGroupResponseTransfer;
        }

        $saveOrderTransfer = $this->buildSaveOrderTransfer($orderTransfer);
        $shipmentGroupTransfer = $this->setShipmentMethod($shipmentGroupTransfer, $orderTransfer);

        $expenseTransfer = $this->createShippingExpenseTransfer($shipmentGroupTransfer->getShipment(), $orderTransfer);
        $orderTransfer = $this->addShippingExpenseToOrderExpenses($orderTransfer, $expenseTransfer);

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
     * @return \Generated\Shared\Transfer\ExpenseTransfer|null
     */
    protected function createShippingExpenseTransfer(
        ShipmentTransfer $shipmentTransfer,
        OrderTransfer $orderTransfer
    ): ?ExpenseTransfer {
        $shipmentMethodHashKey = $this->shipmentService->getShipmentHashKey($shipmentTransfer);
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            $expenseShipmentTransfer = $expenseTransfer->getShipment();
            if ($expenseShipmentTransfer === null) {
                continue;
            }

            if (!$this->isShipmentEqualToShipmentHash($expenseShipmentTransfer, $shipmentMethodHashKey)) {
                continue;
            }

            $expenseTransfer->setShipment($shipmentTransfer);

            return $expenseTransfer;
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer|null $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function addShippingExpenseToOrderExpenses(
        OrderTransfer $orderTransfer,
        ?ExpenseTransfer $expenseTransfer
    ): OrderTransfer {
        if ($expenseTransfer === null) {
            return $orderTransfer;
        }

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
    protected function removeExistingShippingExpenseFromOrderExpenses(
        ExpenseTransfer $expenseTransfer,
        OrderTransfer $orderTransfer
    ): OrderTransfer {
        $orderExpensesCollection = new ArrayObject();

        foreach ($orderTransfer->getExpenses() as $orderExpenseTransfer) {
            if ($expenseTransfer->getShipment() === $orderExpenseTransfer->getShipment()) {
                continue;
            }

            $orderExpensesCollection->append($expenseTransfer);
        }

        $orderTransfer->setExpenses($orderExpensesCollection);

        return $orderTransfer;
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
     * @param string $shipmentMethodHashKey
     *
     * @return bool
     */
    protected function isShipmentEqualToShipmentHash(
        ShipmentTransfer $shipmentTransfer,
        string $shipmentMethodHashKey
    ): bool {
        return $this->shipmentService->getShipmentHashKey($shipmentTransfer) === $shipmentMethodHashKey;
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
        $idShipment = $shipmentTransfer->getIdSalesShipment();
        foreach ($orderShipmentGroupTransfers as $orderShipmentGroupTransfer) {
            $idOrderShipment = $orderShipmentGroupTransfer->requireShipment()->getShipment()->getIdSalesShipment();
            if ($orderShipmentGroupTransfer->getHash() === $shipmentHasKey && $idShipment !== $idOrderShipment) {
                return false;
            }
        }

        return true;
    }
}
