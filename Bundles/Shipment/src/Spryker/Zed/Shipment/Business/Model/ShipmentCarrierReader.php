<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrier;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class ShipmentCarrierReader
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
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer[]
     */
    public function findCarriers()
    {
        $query = $this->shipmentQueryContainer
            ->queryCarriers();

        $shipmentCarrierTransfers = [];

        foreach ($query->find() as $spyShipmentCarrier) {
            $shipmentCarrierTransfer = new ShipmentCarrierTransfer();
            $shipmentCarrierTransfer = $this->mapEntityToTransfer($spyShipmentCarrier, $shipmentCarrierTransfer);
            $shipmentCarrierTransfers[] = $shipmentCarrierTransfer;
        }

        return $shipmentCarrierTransfers;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentCarrier $spyShipmentCarrier
     * @param \Generated\Shared\Transfer\ShipmentCarrierTransfer $shipmentCarrierTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer
     */
    protected function mapEntityToTransfer(SpyShipmentCarrier $spyShipmentCarrier, ShipmentCarrierTransfer $shipmentCarrierTransfer)
    {
        $shipmentCarrierTransfer->fromArray($spyShipmentCarrier->toArray(), true);

        return $shipmentCarrierTransfer;
    }

}
