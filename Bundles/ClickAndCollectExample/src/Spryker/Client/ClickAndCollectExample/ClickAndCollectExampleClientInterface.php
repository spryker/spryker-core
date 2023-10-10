<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\ClickAndCollectExample;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;

interface ClickAndCollectExampleClientInterface
{
    /**
     * Specification:
     * - Collects product offer SKUs from `QuoteTransfer.items`.
     * - Retrieves product offers by SKUs from storage.
     * - Filters out shipment types without product offer shipment type relation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    public function filterUnavailableProductOfferShipmentTypes(
        ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer,
        QuoteTransfer $quoteTransfer
    ): ShipmentTypeStorageCollectionTransfer;
}
