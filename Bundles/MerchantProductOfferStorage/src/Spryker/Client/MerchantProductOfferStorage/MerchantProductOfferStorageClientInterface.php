<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
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
     * - Checks selected attribute.
     * - Validates checked product offer reference attribute.
     * - Resolves default product offer reference by plugin.
     * - Returns the product offer reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return string|null
     */
    public function findProductConcreteDefaultProductOffer(ProductViewTransfer $productViewTransfer): ?string;

    /**
     * Specification:
     * - Finds a product offer within Storage by reference.
     * - Returns null if product offer was not found.
     *
     * @api
     *
     * @param string $productOfferReference
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer|null
     */
    public function findProductOfferByReference(string $productOfferReference): ?ProductOfferStorageTransfer;
}
