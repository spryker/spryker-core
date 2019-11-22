<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

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
     * - Returns the product offer reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return string|null
     */
    public function findProductConcreteDefaultProductOffer(ProductViewTransfer $productViewTransfer): ?string;
}
