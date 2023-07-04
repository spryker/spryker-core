<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShipmentTypeStorage;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;

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
}
