<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentExtension\Dependency\Plugin;

interface ShipmentGroupsSanitizerPluginInterface
{
    /**
     * Specification:
     *  - Sanitize shipment group collection.
     *
     * @api
     *
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function sanitizeShipmentGroupCollection(iterable $shipmentGroupCollection): iterable;
}
