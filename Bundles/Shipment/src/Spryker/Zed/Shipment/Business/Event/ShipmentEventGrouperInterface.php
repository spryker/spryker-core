<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Event;

use ArrayObject;

interface ShipmentEventGrouperInterface
{
    /**
     * @param array $events
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers
     *
     * @return array
     */
    public function groupEventsByShipment(array $events, ArrayObject $orderItemTransfers): array;
}
