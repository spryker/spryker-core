<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Deleter;

interface ProductOfferShipmentTypeStorageDeleterInterface
{
    /**
     * @param list<int> $productOfferIds
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteProductOfferShipmentTypeStoragesByProductOfferIds(array $productOfferIds, ?string $storeName = null): void;

    /**
     * @param list<string> $productOfferReferences
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteProductOfferShipmentTypeStoragesByProductOfferReferences(array $productOfferReferences, ?string $storeName = null): void;
}
