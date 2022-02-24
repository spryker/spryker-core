<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ProductStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductOfferStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves product offer from storage by product concrete SKUs provided in the `ProductOfferStorageCriteriaTransfer`.
     * - Executes {@link \Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageExpanderPluginInterface } plugin stack to expand `ProductOfferStorageTransfer` with additional data.
     * - Executes {@link \Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageFilterPluginInterface} plugin stack to filter `ProductOfferStorageTransfer`s by criteria.
     * - Finds and marks the default product's offer in `ProductOfferStorageTransfer.isDefault`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function getProductOfferStoragesBySkus(
        ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
    ): ProductOfferStorageCollectionTransfer;

    /**
     * Specification:
     * - Retrieves product offers by product concrete SKUs.
     * - Executes {@link \Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferReferenceStrategyPluginInterface} plugin stack to find product offer reference.
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
     * @param array<string> $productOfferReferences
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferStorageTransfer>
     */
    public function getProductOfferStoragesByReferences(array $productOfferReferences): array;

    /**
     * Specification:
     * - Expands the transfer object with the product offer reference according to provided criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\ProductStorageCriteriaTransfer|null $productStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(
        ProductViewTransfer $productViewTransfer,
        ?ProductStorageCriteriaTransfer $productStorageCriteriaTransfer
    ): ProductViewTransfer;
}
