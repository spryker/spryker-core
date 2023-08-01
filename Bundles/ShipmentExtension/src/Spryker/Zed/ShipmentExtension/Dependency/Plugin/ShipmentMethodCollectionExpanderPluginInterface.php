<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;

/**
 * Provides an ability to expand `ShipmentMethodCollectionTransfer` with additional data.
 *
 * Implement this plugin interface to expand `ShipmentMethodCollectionTransfer` after the fetching shipment method data from the persistence.
 */
interface ShipmentMethodCollectionExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands `ShipmentMethodCollectionTransfer` with an additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer
     */
    public function expand(ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer): ShipmentMethodCollectionTransfer;
}
