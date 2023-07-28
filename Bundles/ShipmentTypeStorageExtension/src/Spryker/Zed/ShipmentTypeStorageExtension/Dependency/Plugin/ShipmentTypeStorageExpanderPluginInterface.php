<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorageExtension\Dependency\Plugin;

/**
 * Provides ability to expand `ShipmentTypeStorageTransfer` transfer objects.
 */
interface ShipmentTypeStorageExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands list of `ShipmentTypeStorageTransfer` objects during publishing.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     *
     * @return list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    public function expand(array $shipmentTypeStorageTransfers): array;
}
