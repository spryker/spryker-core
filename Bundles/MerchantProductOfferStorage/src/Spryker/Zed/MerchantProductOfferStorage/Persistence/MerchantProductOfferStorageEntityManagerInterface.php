<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface MerchantProductOfferStorageEntityManagerInterface
{
    /**
     * @param string $concreteSku
     * @param array $data
     * @param string $storeName
     *
     * @return void
     */
    public function saveProductConcreteProductOffersStorage(string $concreteSku, array $data, string $storeName): void;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return void
     */
    public function saveProductOfferStorage(ProductOfferTransfer $productOfferTransfer): void;

    /**
     * @param string[] $productSkus
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteProductConcreteProductOffersStorageEntitiesByProductSkus(array $productSkus, ?string $storeName = null): void;

    /**
     * @param string[] $productOfferReferences
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteProductOfferStorageEntitiesByProductOfferReferences(array $productOfferReferences, ?string $storeName = null): void;
}
