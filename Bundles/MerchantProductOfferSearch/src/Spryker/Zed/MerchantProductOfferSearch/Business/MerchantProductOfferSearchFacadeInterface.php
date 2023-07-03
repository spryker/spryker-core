<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductAbstractMerchantCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractMerchantCriteriaTransfer;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;

interface MerchantProductOfferSearchFacadeInterface
{
    /**
     * Specification:
     *  - Gets merchant ids from eventTransfers.
     *  - Retrieves a list of abstract product ids by merchant ids.
     *  - Queries all product abstract with the given abstract product ids.
     *  - Stores data as json encoded to storage table.
     *  - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdMerchantEvents(array $eventTransfers): void;

    /**
     * Specification:
     *  - Gets merchant product offer ids from eventTransfers.
     *  - Retrieves a list of abstract product ids by product offer ids.
     *  - Queries all product abstract with the given abstract product ids.
     *  - Stores data as json encoded to storage table.
     *  - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdProductOfferEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Gets merchant product offer ids from eventTransfers.
     * - Retrieves a list of product ids by product offer ids.
     * - Publishes concrete products with given ids.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeProductConcreteCollectionByProductOfferEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Gets merchant product offer ids from eventTransfers.
     * - Retrieves a list of product ids by product offer ids.
     * - Publishes concrete products with given ids.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeProductConcreteCollectionByProductOfferStoreEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Returns a list of ProductAbstractMerchantTransfers with data about merchant by product abstract ids.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\MerchantProductOfferSearch\Business\MerchantProductOfferSearchFacadeInterface::getProductAbstractMerchantCollection()} instead.
     *
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractMerchantTransfer>
     */
    public function getProductAbstractMerchantDataByProductAbstractIds(array $productAbstractIds): array;

    /**
     * Specification:
     * - Expands the provided `PageMap.fullTextBoosted` transfer property with merchant names and references from related product offers.
     * - Expands the provided `PageMap` transfer object with related merchant references.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param array<string, mixed> $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function expandProductConcretePageMap(
        PageMapTransfer $pageMapTransfer,
        PageMapBuilderInterface $pageMapBuilder,
        array $productData,
        LocaleTransfer $localeTransfer
    ): PageMapTransfer;

    /**
     * Specification:
     * - Retrieves product abstract merchant data from Persistence.
     * - Requires `ProductAbstractMerchantCriteriaTransfer.productAbstractMerchantConditions` to be set.
     * - Filters by `ProductAbstractMerchantCriteriaTransfer.productAbstractMerchantConditions.productAbstractIds`.
     * - Returns an empty collection if `ProductAbstractMerchantCriteriaTransfer.productAbstractMerchantConditions.productAbstractIds` is empty.
     * - If `ProductAbstractMerchantCriteriaTransfer.productAbstractMerchantConditions.isProductOfferActive` is provided
     * filters by product offer active status.
     * - If `ProductAbstractMerchantCriteriaTransfer.productAbstractMerchantConditions.productOfferApprovalStatuses`
     * is not empty filters by product offer approval status.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractMerchantCriteriaTransfer $productAbstractMerchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractMerchantCollectionTransfer
     */
    public function getProductAbstractMerchantCollection(
        ProductAbstractMerchantCriteriaTransfer $productAbstractMerchantCriteriaTransfer
    ): ProductAbstractMerchantCollectionTransfer;
}
