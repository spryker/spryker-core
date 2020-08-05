<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Dependency\Service;

use ArrayObject;

class MerchantSalesOrderGuiToShipmentServiceBridge implements MerchantSalesOrderGuiToShipmentServiceInterface
{
    /**
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    private $shipmentService;

    /**
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     */
    public function __construct($shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransferCollection
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function groupItemsByShipment(iterable $itemTransferCollection): ArrayObject
    {
        return $this->shipmentService->groupItemsByShipment($itemTransferCollection);
    }
}
