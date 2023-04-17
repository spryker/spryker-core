<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Dependency\Service;

use ArrayObject;

class PickingListMultiShipmentPickingStrategyExampleToShipmentServiceBridge implements PickingListMultiShipmentPickingStrategyExampleToShipmentServiceInterface
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
     * @param iterable<\Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ShipmentGroupTransfer>
     */
    public function groupItemsByShipment(iterable $itemTransferCollection): ArrayObject
    {
        return $this->shipmentService->groupItemsByShipment($itemTransferCollection);
    }
}
