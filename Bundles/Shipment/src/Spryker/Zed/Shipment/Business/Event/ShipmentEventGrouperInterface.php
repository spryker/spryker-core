<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Event;

interface ShipmentEventGrouperInterface
{
    /**
     * @param array $events
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers
     *
     * @return string[][]
     */
    public function groupEventsByShipment(array $events, iterable $orderItemTransfers): array;
}
