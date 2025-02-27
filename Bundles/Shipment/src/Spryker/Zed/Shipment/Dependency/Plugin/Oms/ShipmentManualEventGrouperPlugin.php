<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Dependency\Plugin\Oms;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OmsExtension\Dependency\Plugin\OmsManualEventGrouperPluginInterface;

/**
 * @method \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface getFacade()
 */
class ShipmentManualEventGrouperPlugin extends AbstractPlugin implements OmsManualEventGrouperPluginInterface
{
    /**
     * {@ingheritDoc}
     *  - Groups manual events by sales shipment id.
     *
     * @api
     *
     * @param array<array<string>> $events
     * @param iterable<\Generated\Shared\Transfer\ItemTransfer> $orderItemTransfers
     *
     * @return array<array<string>>
     */
    public function group(array $events, iterable $orderItemTransfers): array
    {
        return $this->getFacade()->groupEventsByShipment($events, $orderItemTransfers);
    }
}
