<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypesRestApi\Business\Reader;

use Spryker\Zed\ShipmentTypesRestApi\Dependency\Facade\ShipmentTypesRestApiToShipmentFacadeInterface;

class ShipmentMethodReader implements ShipmentMethodReaderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentTypesRestApi\Dependency\Facade\ShipmentTypesRestApiToShipmentFacadeInterface
     */
    protected ShipmentTypesRestApiToShipmentFacadeInterface $shipmentFacade;

    /**
     * @param \Spryker\Zed\ShipmentTypesRestApi\Dependency\Facade\ShipmentTypesRestApiToShipmentFacadeInterface $shipmentFacade
     */
    public function __construct(ShipmentTypesRestApiToShipmentFacadeInterface $shipmentFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @return array<int, \Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    public function getShipmentMethodTransfersIndexedByIdShipmentMethod(): array
    {
        $shipmentMethodTransfers = $this->shipmentFacade->getMethods();

        $shipmentMethodTransfersIndexedById = [];
        foreach ($shipmentMethodTransfers as $shipmentMethodTransfer) {
            $shipmentMethodTransfersIndexedById[$shipmentMethodTransfer->getIdShipmentMethodOrFail()] = $shipmentMethodTransfer;
        }

        return $shipmentMethodTransfersIndexedById;
    }
}
