<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Dependency\Service;

class SalesToShipmentServiceBridge implements SalesToShipmentServiceInterface
{
    /**
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     */
    public function __construct($shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function groupItemsByShipment(iterable $itemTransfers): array
    {
        return $this->shipmentService->groupItemsByShipment($itemTransfers);
    }
}
