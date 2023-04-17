<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Dependency\Service;

use ArrayObject;

interface PickingListMultiShipmentPickingStrategyExampleToShipmentServiceInterface
{
    /**
     * @param iterable<\Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ShipmentGroupTransfer>
     */
    public function groupItemsByShipment(iterable $itemTransferCollection): ArrayObject;
}
