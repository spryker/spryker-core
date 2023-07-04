<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence;

use Generated\Shared\Transfer\ProductOfferShipmentTypeStorageTransfer;

interface ProductOfferShipmentTypeStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeStorageTransfer $productOfferShipmentTypeStorageTransfer
     * @param string $storeName
     *
     * @return void
     */
    public function saveProductOfferShipmentTypeStorage(
        ProductOfferShipmentTypeStorageTransfer $productOfferShipmentTypeStorageTransfer,
        string $storeName
    ): void;

    /**
     * @param list<string> $productOfferReferences
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteProductOfferShipmentTypeStorages(array $productOfferReferences, ?string $storeName = null): void;
}
