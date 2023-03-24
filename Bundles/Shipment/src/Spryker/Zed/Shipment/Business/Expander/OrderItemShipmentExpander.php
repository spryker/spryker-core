<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentConditionsTransfer;
use Generated\Shared\Transfer\ShipmentCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Shipment\Business\Grouper\ItemGrouperInterface;
use Spryker\Zed\Shipment\Business\Grouper\ShipmentGrouperInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

class OrderItemShipmentExpander implements OrderItemShipmentExpanderInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Business\Grouper\ItemGrouperInterface
     */
    protected $itemGrouper;

    /**
     * @var \Spryker\Zed\Shipment\Business\Grouper\ShipmentGrouperInterface
     */
    protected $shipmentGrouper;

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface
     */
    protected $shipmentRepository;

    /**
     * @param \Spryker\Zed\Shipment\Business\Grouper\ItemGrouperInterface $itemGrouper
     * @param \Spryker\Zed\Shipment\Business\Grouper\ShipmentGrouperInterface $shipmentGrouper
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     */
    public function __construct(
        ItemGrouperInterface $itemGrouper,
        ShipmentGrouperInterface $shipmentGrouper,
        ShipmentRepositoryInterface $shipmentRepository
    ) {
        $this->itemGrouper = $itemGrouper;
        $this->shipmentGrouper = $shipmentGrouper;
        $this->shipmentRepository = $shipmentRepository;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithShipment(array $itemTransfers): array
    {
        $shipmentCollectionTransfer = $this->shipmentRepository
            ->getSalesShipmentCollection(
                $this->createShipmentCriteriaTransfer($itemTransfers),
            );

        $itemTransferCollectionIndexedByIdSalesOrderItem = $this->itemGrouper
            ->getItemTransferCollectionIndexedByIdSalesOrderItem(
                $itemTransfers,
            );

        $shipmentTransferCollectionIndexedByIdSalesShipment = $this->shipmentGrouper
            ->getShipmentTransferCollectionIndexedByIdSalesShipment(
                $shipmentCollectionTransfer,
            );

        if (
            $itemTransferCollectionIndexedByIdSalesOrderItem === [] ||
            $shipmentTransferCollectionIndexedByIdSalesShipment === []
        ) {
                return $itemTransfers;
        }

        $itemIdsGroupedByShipmentIds = $this->shipmentRepository
            ->getItemIdsGroupedByShipmentIds(
                $this->createOrderTransfer($itemTransfers),
            );

        return $this->expandItemTransferCollectionWithShipments(
            $shipmentTransferCollectionIndexedByIdSalesShipment,
            $itemTransferCollectionIndexedByIdSalesOrderItem,
            $itemIdsGroupedByShipmentIds,
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer(array $itemTransfers): OrderTransfer
    {
        $orderTransfer = new OrderTransfer();

        $firstItemTransfer = array_values($itemTransfers)[0] ?? new ItemTransfer();
        $idSalesOrder = $firstItemTransfer->getFkSalesOrder();
        if (!$idSalesOrder) {
            return $orderTransfer;
        }

        return $orderTransfer->setIdSalesOrder($idSalesOrder);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ShipmentCriteriaTransfer
     */
    protected function createShipmentCriteriaTransfer(array $itemTransfers): ShipmentCriteriaTransfer
    {
        $shipmentConditionsTransfer = new ShipmentConditionsTransfer();
        foreach ($itemTransfers as $itemTransfer) {
            $shipmentConditionsTransfer->addIdSalesOrderItem(
                $itemTransfer->getIdSalesOrderItem(),
            );
        }

        return (new ShipmentCriteriaTransfer())->setShipmentConditions($shipmentConditionsTransfer);
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\ShipmentTransfer> $shipmentTransferCollectionIndexedByIdSalesShipment
     * @param array<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransferCollectionIndexedByIdSalesOrderItem
     * @param array<int, list<int>> $itemIdsGroupedByShipmentIds
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function expandItemTransferCollectionWithShipments(
        array $shipmentTransferCollectionIndexedByIdSalesShipment,
        array $itemTransferCollectionIndexedByIdSalesOrderItem,
        array $itemIdsGroupedByShipmentIds
    ): array {
        foreach ($itemIdsGroupedByShipmentIds as $idSalesShipment => $idSalesOrderItemCollection) {
            $shipmentTransfer = $shipmentTransferCollectionIndexedByIdSalesShipment[$idSalesShipment] ?? null;
            if (!$shipmentTransfer) {
                continue;
            }

            $itemTransferCollectionIndexedByIdSalesOrderItem = $this->expandItemTransferCollectionWithShipmentTransfer(
                $shipmentTransfer,
                $itemTransferCollectionIndexedByIdSalesOrderItem,
                $idSalesOrderItemCollection,
            );
        }

        return array_values($itemTransferCollectionIndexedByIdSalesOrderItem);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param array<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransferCollectionIndexedByIdSalesOrderItem
     * @param list<int> $idSalesOrderItemCollection
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function expandItemTransferCollectionWithShipmentTransfer(
        ShipmentTransfer $shipmentTransfer,
        array $itemTransferCollectionIndexedByIdSalesOrderItem,
        array $idSalesOrderItemCollection
    ): array {
        foreach ($idSalesOrderItemCollection as $idSalesOrderItem) {
            if (!isset($itemTransferCollectionIndexedByIdSalesOrderItem[$idSalesOrderItem])) {
                continue;
            }

            $itemTransferCollectionIndexedByIdSalesOrderItem[$idSalesOrderItem]->setShipment($shipmentTransfer);
        }

        return $itemTransferCollectionIndexedByIdSalesOrderItem;
    }
}
