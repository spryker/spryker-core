<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Shipment\Business\ShipmentFacadeInterface;

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
}
