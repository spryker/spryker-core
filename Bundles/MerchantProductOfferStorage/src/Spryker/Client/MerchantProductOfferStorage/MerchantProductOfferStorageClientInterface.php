<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;

interface MerchantProductOfferStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves product offer from storage by product concrete SKUs provided in the `ProductOfferStorageCriteriaTransfer`.
     * - Optionally filters the collection by merchant reference.
     * - Expands `ProductOfferStorageTransfer`s with relevant `MerchantStorageTransfer`.
     * - Filters out `ProductOfferStorageTransfer` if relevant merchant is not found.
     * - Finds and marks the default product's offer in `ProductOfferStorageTransfer.isDefault`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function getProductOffersBySkus(
        ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
    ): ProductOfferStorageCollectionTransfer;

    /**
     * Specification:
     * - Retrieves product offers by product concrete SKUs.
     * - Optionally filters the collection by merchant reference.
     * - Returns null if product offer references does not exist.
     * - Returns `ProductOfferStorageCriteriaTransfer.productOfferReference` of it is found in the retrieved collection.
     * - Resolves default product offer reference by plugin.
     * - Returns the product offer reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return string|null
     */
    public function findProductConcreteDefaultProductOffer(ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer): ?string;

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
    public function findProductOfferStorageByReference(string $productOfferReference): ?ProductOfferStorageTransfer;

    /**
     * Specification:
     * - Finds product offers within Storage by references.
     *
     * @api
     *
     * @param string[] $productOfferReferences
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer[]
     */
    public function getProductOfferStorageByReferences(array $productOfferReferences): array;
}
