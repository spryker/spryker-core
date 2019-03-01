<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Orm\Zed\Shipment\Persistence\Map\SpyShipmentMethodTableMap;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class ShipmentOrderHydrate implements ShipmentOrderHydrateInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected $shipmentQueryContainer;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $shipmentQueryContainer
     */
    public function __construct(ShipmentQueryContainerInterface $shipmentQueryContainer)
    {
        $this->shipmentQueryContainer = $shipmentQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderWithShipment(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireIdSalesOrder();

        $idSalesOrder = $orderTransfer->getIdSalesOrder();

        $salesOrderShipments = $this->shipmentQueryContainer
            ->querySalesShipmentByIdSalesOrder($idSalesOrder)
            ->find();

        if ($salesOrderShipments->count() === 1) {
            $orderTransfer = $this->hydrateShipmentMethodToOrderTransfer($salesOrderShipments, $orderTransfer);

            return $this->setShipmentToOrderExpenses($salesOrderShipments, $orderTransfer);
        }

        $orderTransfer = $this->hydrateMultiShipmentMethodToOrderTransfer($salesOrderShipments, $orderTransfer);

        return $this->setShipmentToOrderExpenses($salesOrderShipments, $orderTransfer);
    }

    /**
     * @param iterable|\Orm\Zed\Sales\Persistence\SpySalesShipment[]|\Propel\Runtime\Collection\ObjectCollection $salesOrderShipments
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function hydrateMultiShipmentMethodToOrderTransfer(
        iterable $salesOrderShipments,
        OrderTransfer $orderTransfer
    ) {
        foreach ($salesOrderShipments as $salesShipmentEntity) {
            $shipmentMethodEntity = $this->shipmentQueryContainer
                ->queryActiveMethods()
                ->findOneByName($salesShipmentEntity->getName());

            $shipmentMethodTransfer = new ShipmentMethodTransfer();
            $shipmentMethodTransfer = $this->hydrateShipmentMethodTransferFromShipmentMethod($shipmentMethodTransfer, $shipmentMethodEntity);
            $shipmentMethodTransfer = $this->hydrateShipmentMethodTransferFromSalesShipment($shipmentMethodTransfer, $salesShipmentEntity);

            $orderTransfer = $this->setShipmentMethodToItems($orderTransfer, $shipmentMethodTransfer);

            $orderTransfer->addShipmentMethod($shipmentMethodTransfer);
            $orderTransfer = $this->hydrateSalesShipmentToOrderItem($salesShipmentEntity, $orderTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param iterable|\Orm\Zed\Sales\Persistence\SpySalesShipment[]|\Propel\Runtime\Collection\ObjectCollection $salesOrderShipments
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function hydrateShipmentMethodToOrderTransfer(
        iterable $salesOrderShipments,
        OrderTransfer $orderTransfer
    ) {
        $shipmentTransfer = new ShipmentTransfer();
        $shipmentTransfer->setShippingAddress($orderTransfer->getShippingAddress());
        $salesShipmentEntity = $salesOrderShipments->getFirst();

        $shipmentMethodEntity = $this->shipmentQueryContainer
            ->queryActiveMethods()
            ->findOneByName($salesShipmentEntity->getName());

        $shipmentMethodTransfer = new ShipmentMethodTransfer();
        $shipmentMethodTransfer = $this->hydrateShipmentMethodTransferFromShipmentMethod($shipmentMethodTransfer, $shipmentMethodEntity);
        $shipmentMethodTransfer = $this->hydrateShipmentMethodTransferFromSalesShipment($shipmentMethodTransfer, $salesShipmentEntity);

        $shipmentTransfer = $this->hydrateSalesShipmentToShipmentTransfer($salesShipmentEntity, $shipmentTransfer);
        $orderTransfer = $this->addShipmentToOrderItems($orderTransfer, $shipmentTransfer);
        $orderTransfer = $this->addShipmentMethodToOrderItems($orderTransfer, $shipmentMethodTransfer);
        /**
         * @todo set order->shipment to null.
         */

        $orderTransfer->addShipmentMethod($shipmentMethodTransfer);
        $orderTransfer = $this->hydrateSalesShipmentToOrderItem($salesShipmentEntity, $orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipmentEntity
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function hydrateSalesShipmentToShipmentTransfer(SpySalesShipment $salesShipmentEntity, ShipmentTransfer $shipmentTransfer): ShipmentTransfer
    {
        return $shipmentTransfer->fromArray($salesShipmentEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function addShipmentToOrderItems(OrderTransfer $orderTransfer, ShipmentTransfer $shipmentTransfer): OrderTransfer
    {
        foreach ($orderTransfer->getItems() as $item) {
            $item->setShipment($shipmentTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function addShipmentMethodToOrderItems(OrderTransfer $orderTransfer, ShipmentMethodTransfer $shipmentMethodTransfer): OrderTransfer
    {
        foreach ($orderTransfer->getItems() as $item) {
            $item->getShipment()->setMethod($shipmentMethodTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipmentEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function hydrateSalesShipmentToOrderItem(SpySalesShipment $salesShipmentEntity, OrderTransfer $orderTransfer): OrderTransfer
    {
        foreach ($salesShipmentEntity->getSpySalesOrderItems() as $itemEntity) {
            foreach ($orderTransfer->getItems() as $itemTransfer) {
                $this->setFkSalesShipmentToOrderItem($itemEntity, $itemTransfer);
            }
        }

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem $orderItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function setFkSalesShipmentToOrderItem(SpySalesOrderItem $orderItemEntity, ItemTransfer $itemTransfer): ItemTransfer
    {
        if ($itemTransfer->getIdSalesOrderItem() === $orderItemEntity->getIdSalesOrderItem()) {
            $itemTransfer->getShipment()->setIdSalesShipment($orderItemEntity->getFkSalesShipment());
        }

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function setShipmentMethodToItems(OrderTransfer $orderTransfer, ShipmentMethodTransfer $shipmentMethodTransfer): OrderTransfer
    {
        foreach ($orderTransfer->getItems() as $item) {
            if ($item->getShipment()->getMethod()->getName() === $shipmentMethodTransfer->getName()) {
                $item->getShipment()->setMethod($shipmentMethodTransfer);
            }
        }

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment[]|\Propel\Runtime\Collection\ObjectCollection $salesOrderShipments
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function setShipmentToOrderExpenses(ObjectCollection $salesOrderShipments, OrderTransfer $orderTransfer): OrderTransfer
    {
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            $shipmentEntity = null;
            foreach ($salesOrderShipments as $salesShipmentEntity) {
                if ($salesShipmentEntity->getFkSalesExpense() === $expenseTransfer->getIdSalesExpense()) {
                    $shipmentEntity = $salesShipmentEntity;
                    break;
                }
            }

            if ($shipmentEntity === null) {
                continue;
            }

            foreach ($orderTransfer->getItems() as $itemTransfer) {
                if ($itemTransfer->getShipment()->getIdSalesShipment() === $shipmentEntity->getIdSalesShipment()) {
                    $expenseTransfer->setShipment($itemTransfer->getShipment());
                }
            }
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $shipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function hydrateShipmentMethodTransferFromShipmentMethod(ShipmentMethodTransfer $shipmentMethodTransfer, SpyShipmentMethod $shipmentMethod): ShipmentMethodTransfer
    {
        return $shipmentMethodTransfer->fromArray($shipmentMethod->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function hydrateShipmentMethodTransferFromSalesShipment(ShipmentMethodTransfer $shipmentMethodTransfer, SpySalesShipment $salesShipment): ShipmentMethodTransfer
    {
        return $shipmentMethodTransfer->fromArray($salesShipment->toArray(), true);
    }


    //---------

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment[]|\Propel\Runtime\Collection\ObjectCollection $salesOrderShipments
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function hydrateShipmentMethodToOrderTransfer2(
        ObjectCollection $salesOrderShipments,
        OrderTransfer $orderTransfer
    ) {

        foreach ($salesOrderShipments as $salesShipmentEntity) {
            $idShipmentMethod = $this->shipmentQueryContainer
                ->queryActiveMethods()
                ->select(SpyShipmentMethodTableMap::COL_ID_SHIPMENT_METHOD)
                ->findOneByName($salesShipmentEntity->getName());

            $shipmentMethodTransfer = new ShipmentMethodTransfer();
            $shipmentMethodTransfer->fromArray($salesShipmentEntity->toArray(), true);
            if ($idShipmentMethod) {
                $shipmentMethodTransfer->setIdShipmentMethod($idShipmentMethod);
            }
            $orderTransfer->addShipmentMethod($shipmentMethodTransfer);
        }

        return $orderTransfer;
    }
}
