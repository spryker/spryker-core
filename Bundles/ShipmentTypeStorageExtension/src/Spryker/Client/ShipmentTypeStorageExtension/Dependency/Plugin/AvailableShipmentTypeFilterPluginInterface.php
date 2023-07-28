<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;

/**
 * Allows filtering out unavailable shipment types.
 */
interface AvailableShipmentTypeFilterPluginInterface
{
    /**
     * Specification:
     * - Filters out not available shipment types for provided `QuoteTransfer`
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    public function filter(
        ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer,
        QuoteTransfer $quoteTransfer
    ): ShipmentTypeStorageCollectionTransfer;
}
