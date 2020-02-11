<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApprovalShipmentConnector\Dependency\Service;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentTransfer;

class QuoteApprovalShipmentConnectorToShipmentServiceBridge implements QuoteApprovalShipmentConnectorToShipmentServiceInterface
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
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransferCollection
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function groupItemsByShipment(iterable $itemTransferCollection): ArrayObject
    {
        return $this->shipmentService->groupItemsByShipment($itemTransferCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return string
     */
    public function getShipmentHashKey(ShipmentTransfer $shipmentTransfer): string
    {
        return $this->shipmentService->getShipmentHashKey($shipmentTransfer);
    }
}
