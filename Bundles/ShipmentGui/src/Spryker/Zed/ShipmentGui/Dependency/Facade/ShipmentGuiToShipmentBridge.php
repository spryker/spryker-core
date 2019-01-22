<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Dependency\Facade;

use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class ShipmentGuiToShipmentBridge implements ShipmentGuiToShipmentInterface
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
     * @inheritdoc
     */
    public function findShipmentById(int $idShipment): ?ShipmentTransfer
    {
        return $this->shipmentFacade->findShipmentById($idShipment);
    }

    /**
     * @inheritdoc
     */
    public function updateShipmentTransaction(ShipmentGroupTransfer $shipmentGroupTransfer): void
    {
        $this->shipmentFacade->updateShipmentTransaction($shipmentGroupTransfer);
    }

    /**
     * @inheritdoc
     */
    public function findShipmentItemsByIsSalesShipment(int $idSalesShipment): ObjectCollection
    {
        return $this->shipmentFacade->findShipmentItemsByIdSalesShipment($idSalesShipment);
    }
}
