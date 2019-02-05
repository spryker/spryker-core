<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
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

        return $this->hydrateShipmentMethodToOrderTransfer($salesOrderShipments, $orderTransfer);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment[]|\Propel\Runtime\Collection\ObjectCollection $salesOrderShipments
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function hydrateShipmentMethodToOrderTransfer(
        ObjectCollection $salesOrderShipments,
        OrderTransfer $orderTransfer
    ) {

        foreach ($salesOrderShipments as $salesShipmentEntity) {
            $shipmentMethodEntity = $this->shipmentQueryContainer
                ->queryActiveMethods()
                ->findOneByName($salesShipmentEntity->getName());

            $shipmentMethodTransfer = new ShipmentMethodTransfer();
            $shipmentMethodTransfer->fromArray($shipmentMethodEntity->toArray(), true);
            $shipmentMethodTransfer->fromArray($salesShipmentEntity->toArray(), true);
            $idShipmentMethod = $shipmentMethodTransfer->getIdShipmentMethod();
            if ($idShipmentMethod) {
                $shipmentMethodTransfer->setIdShipmentMethod($idShipmentMethod);
            }

            foreach ($orderTransfer->getItems() as $item) {
                if ($item->getShipment()->getMethod()->getName() === $shipmentMethodTransfer->getName()) {
                    $item->getShipment()->setMethod($shipmentMethodTransfer);
                }
            }

            $orderTransfer->addShipmentMethod($shipmentMethodTransfer);
        }

        return $orderTransfer;
    }
}
