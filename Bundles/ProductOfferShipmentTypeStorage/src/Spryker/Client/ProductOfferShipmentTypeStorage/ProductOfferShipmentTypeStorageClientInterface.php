<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShipmentTypeStorage;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;

interface ProductOfferShipmentTypeStorageClientInterface
{
    /**
     * Specification:
     * - Requires `ProductOfferStorageTransfer.productOfferReference` to be set.
     * - Builds a key and retrieves product offer shipment type data from Storage by the key.
     * - Extracts shipment type UUIDs from product offer shipment type data.
     * - Uses {@link \Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageClientInterface::getShipmentTypeStorageCollection()} to retrieve shipment type data from Storage.
     * - Returns `ProductOfferStorageTransfer` expanded with shipment type storage data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function expandProductOfferStorageWithShipmentTypes(ProductOfferStorageTransfer $productOfferStorageTransfer): ProductOfferStorageTransfer;

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
        QuoteTransfer $quoteTransfer,
    ): ShipmentTypeStorageCollectionTransfer;
}
