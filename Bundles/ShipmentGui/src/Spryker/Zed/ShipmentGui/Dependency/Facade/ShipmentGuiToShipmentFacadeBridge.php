<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Dependency\Facade;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentCarrierRequestTransfer;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentGroupResponseTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

class ShipmentGuiToShipmentFacadeBridge implements ShipmentGuiToShipmentFacadeInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface $shipmentFacade
     */
    public function __construct($shipmentFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function getMethods()
    {
        return $this->shipmentFacade->getMethods();
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findMethodById($idShipmentMethod): ?ShipmentMethodTransfer
    {
        return $this->shipmentFacade->findMethodById($idShipmentMethod);
    }

    /**
     * @param int $idSalesShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    public function findShipmentById(int $idSalesShipment): ?ShipmentTransfer
    {
        return $this->shipmentFacade->findShipmentById($idSalesShipment);
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
        return $this->shipmentFacade->saveShipment($shipmentGroupTransfer, $orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param bool[] $itemListUpdatedStatus
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function createShipmentGroupTransferWithListedItems(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        array $itemListUpdatedStatus
    ): ShipmentGroupTransfer {
        return $this->shipmentFacade
            ->createShipmentGroupTransferWithListedItems($shipmentGroupTransfer, $itemListUpdatedStatus);
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesShipment
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]|\ArrayObject
     */
    public function findSalesOrderItemsIdsBySalesShipmentId(int $idSalesOrder, int $idSalesShipment): ArrayObject
    {
        return $this->shipmentFacade->findSalesOrderItemsIdsBySalesShipmentId($idSalesOrder, $idSalesShipment);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentCarrierRequestTransfer $shipmentCarrierRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer|null
     */
    public function findShipmentCarrier(ShipmentCarrierRequestTransfer $shipmentCarrierRequestTransfer): ?ShipmentCarrierTransfer
    {
        return $this->shipmentFacade->findShipmentCarrier($shipmentCarrierRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentCarrierTransfer $carrierTransfer
     *
     * @return int
     */
    public function createCarrier(ShipmentCarrierTransfer $carrierTransfer)
    {
        return $this->shipmentFacade->createCarrier($carrierTransfer);
    }

    /**
     * @param string $shipmentMethodName
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodByName(string $shipmentMethodName): ?ShipmentMethodTransfer
    {
        return $this->shipmentFacade->findShipmentMethodByName($shipmentMethodName);
    }

    /**
     * @param string $shipmentMethodKey
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodByKey(string $shipmentMethodKey): ?ShipmentMethodTransfer
    {
        return $this->shipmentFacade->findShipmentMethodByKey($shipmentMethodKey);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return int|null
     */
    public function createMethod(ShipmentMethodTransfer $methodTransfer): ?int
    {
        return $this->shipmentFacade->createMethod($methodTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return int|bool
     */
    public function updateMethod(ShipmentMethodTransfer $methodTransfer)
    {
        return $this->shipmentFacade->updateMethod($methodTransfer);
    }

    /**
     * @param int $idMethod
     *
     * @return bool
     */
    public function deleteMethod($idMethod)
    {
        return $this->shipmentFacade->deleteMethod($idMethod);
    }
}
