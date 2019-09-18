<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Shipment;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Shipment\ShipmentConfig;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

class ShipmentOrderHydrate implements ShipmentOrderHydrateInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface
     */
    protected $shipmentRepository;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        ShipmentRepositoryInterface $shipmentRepository,
        ShipmentToSalesFacadeInterface $salesFacade
    ) {
        $this->shipmentRepository = $shipmentRepository;
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderWithShipment(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireIdSalesOrder();

        $shipmentTransfers = $this->shipmentRepository->findShipmentTransfersByOrder($orderTransfer);
        if (count($shipmentTransfers) === 0) {
            return $orderTransfer;
        }

        $shipmentTransfers = $this->addOrderAddressToShipments($shipmentTransfers, $orderTransfer);

        if ($this->isMultiShipmentOrder($shipmentTransfers)) {
            $orderTransfer = $this->hydrateMultiShipmentMethodToOrderTransfer($shipmentTransfers, $orderTransfer);
        } else {
            $orderTransfer = $this->hydrateShipmentMethodToOrderTransfer($shipmentTransfers, $orderTransfer);
        }

        $orderTransfer = $this->setShipmentToOrderExpenses($orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ShipmentTransfer[] $shipmentTransfers
     *
     * @return bool
     */
    protected function isMultiShipmentOrder(iterable $shipmentTransfers): bool
    {
        return count($shipmentTransfers) > 1;
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ShipmentTransfer[] $shipmentTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function hydrateShipmentMethodToOrderTransfer(
        iterable $shipmentTransfers,
        OrderTransfer $orderTransfer
    ): OrderTransfer {
        /** @var \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer */
        $shipmentTransfer = current($shipmentTransfers);
        $orderTransfer = $this->addShipmentToOrderItems($orderTransfer, $shipmentTransfer);
        $orderTransfer = $this->setOrderLevelShipmentMethod($orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     */
    protected function addShipmentToOrderItems(OrderTransfer $orderTransfer, ShipmentTransfer $shipmentTransfer): OrderTransfer
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ShipmentTransfer[] $shipmentTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function hydrateMultiShipmentMethodToOrderTransfer(
        iterable $shipmentTransfers,
        OrderTransfer $orderTransfer
    ): OrderTransfer {
        $salesOrderItemIdsGroupedByShipmentIds = $this->shipmentRepository
            ->getItemIdsGroupedByShipmentIds(
                $orderTransfer,
                $this->getDefaultShipmentTransferWithOrderLevelShippingAddress($orderTransfer, $shipmentTransfers)
            );

        foreach ($shipmentTransfers as $shipmentTransfer) {
            if (empty($salesOrderItemIdsGroupedByShipmentIds[$shipmentTransfer->getIdSalesShipment()])) {
                continue;
            }

            $idSalesOrderItemListForCurrentShipment = $salesOrderItemIdsGroupedByShipmentIds[$shipmentTransfer->getIdSalesShipment()];
            $orderTransfer = $this->addShipmentToOrderItemsSpecifiedByIdSalesOrderItemList(
                $orderTransfer,
                $shipmentTransfer,
                $idSalesOrderItemListForCurrentShipment
            );
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param int[] $idSalesOrderItemList
     *
     * @return \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     */
    protected function addShipmentToOrderItemsSpecifiedByIdSalesOrderItemList(
        OrderTransfer $orderTransfer,
        ShipmentTransfer $shipmentTransfer,
        array $idSalesOrderItemList
    ): OrderTransfer {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (!in_array($itemTransfer->getIdSalesOrderItem(), $idSalesOrderItemList)) {
                continue;
            }

            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function setShipmentToOrderExpenses(OrderTransfer $orderTransfer): OrderTransfer
    {
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() !== ShipmentConfig::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $shipmentTransfer = $this->findShipmentByOrderExpense($orderTransfer, $expenseTransfer);
            $expenseTransfer->setShipment($shipmentTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    protected function findShipmentByOrderExpense(OrderTransfer $orderTransfer, ExpenseTransfer $expenseTransfer): ?ShipmentTransfer
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $itemShipmentTransfer = $itemTransfer->getShipment();
            if ($itemShipmentTransfer === null) {
                continue;
            }

            $itemShipmentTransfer->requireMethod();
            if ($itemShipmentTransfer->getMethod()->getFkSalesExpense() === $expenseTransfer->getIdSalesExpense()) {
                return $itemTransfer->getShipment();
            }
        }

        return null;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function setOrderLevelShipmentMethod(OrderTransfer $orderTransfer): OrderTransfer
    {
        $firstItemTransfer = current($orderTransfer->getItems());
        $firstItemTransfer->requireShipment()
            ->getShipment()->requireMethod();

        return $orderTransfer->addShipmentMethod($firstItemTransfer->getShipment()->getMethod());
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer[] $shipmentTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer[]
     */
    protected function addOrderAddressToShipments(array $shipmentTransfers, OrderTransfer $orderTransfer): array
    {
        foreach ($shipmentTransfers as $shipmentTransfer) {
            if ($shipmentTransfer->getShippingAddress() === null) {
                $shipmentTransfer->setShippingAddress($orderTransfer->getShippingAddress());
            }
        }

        return $shipmentTransfers;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param iterable|\Generated\Shared\Transfer\ShipmentTransfer[] $shipmentTransfers
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    protected function getDefaultShipmentTransferWithOrderLevelShippingAddress(
        OrderTransfer $orderTransfer,
        iterable $shipmentTransfers
    ): ?ShipmentTransfer {
        if (count($shipmentTransfers) === 0) {
            return null;
        }

        $orderShippingAddressTransfer = $orderTransfer->getShippingAddress();
        if ($orderShippingAddressTransfer === null) {
            return null;
        }

        $idSalesOrderAddress = $orderShippingAddressTransfer->getIdSalesOrderAddress();
        if ($idSalesOrderAddress === null) {
            return null;
        }

        foreach ($shipmentTransfers as $shipmentTransfer) {
            $shipmentAddressTransfer = $shipmentTransfer->getShippingAddress();
            if ($shipmentAddressTransfer === null) {
                continue;
            }

            if ($shipmentAddressTransfer->getIdSalesOrderAddress() === $idSalesOrderAddress) {
                return $shipmentTransfer;
            }
        }

        return null;
    }
}
