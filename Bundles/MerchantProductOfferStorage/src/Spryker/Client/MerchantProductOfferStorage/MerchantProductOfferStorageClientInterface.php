<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;

interface MerchantProductOfferStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves content by key through a storage client dependency.
     * - Returns the product offer collection.
     *
     * @api
     *
     * @param string $productSku
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function getProductOfferStorageCollection(string $productSku): ProductOfferStorageCollectionTransfer;

    /**
     * Specification:
     * - Retrieves content by key through a storage client dependency.
     * - Returns the product offer references.
     *
     * @api
     *
     * @param string $productSku
     *
     * @return string[]
     */
    public function getProductOfferReferences(string $productSku): array;
}
