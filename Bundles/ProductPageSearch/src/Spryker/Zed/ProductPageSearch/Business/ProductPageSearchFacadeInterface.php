<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;

interface ProductPageSearchFacadeInterface
{
    /**
     * Specification:
     * - Queries all productAbstract with the given productAbstractIds.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     * - Executes `ProductPageSearchCollectionFilterPluginInterface` stack of plugins.
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds);

    /**
     * Specification:
     * - Queries all productAbstract with the given productAbstractIds
     * - Stores and update data partially as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     * - $pageDataExpanderPluginNames param is optional and if it's empty
     *      it will call all provided plugins, otherwise only update necessary part
     *      of data which provide in plugin name.
     * - Executes `ProductPageSearchCollectionFilterPluginInterface` stack of plugins.
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     * @param array<string> $pageDataExpanderPluginNames
     *
     * @return void
     */
    public function refresh(array $productAbstractIds, $pageDataExpanderPluginNames = []);

    /**
     * Specification:
     * - Finds and deletes productAbstract storage entities with the given productAbstractIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds);

    /**
     * Specification:
     * - Publishes searchable concrete products with given ids.
     * - Executes a stack of {@link \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcreteCollectionFilterPluginInterface} plugins.
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return void
     */
    public function publishProductConcretes(array $productIds): void;

    /**
     * Specification:
     * - Unpublishes concrete products with given ids.
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return void
     */
    public function unpublishProductConcretes(array $productIds): void;

    /**
     * Specification:
     * - Unpublishes concrete products by given abstract product ids and store names.
     *
     * @api
     *
     * @param array $productAbstractStoreMap Keys are product abstract IDs, values are store IDs.
     *
     * @return void
     */
    public function unpublishProductConcretePageSearches(array $productAbstractStoreMap): void;

    /**
     * Specification:
     * - Finds product concrete page search entities by given concrete product ids.
     * - Returns array of ProductConcretePageSearchTransfer objects.
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcretePageSearchTransfer>
     */
    public function getProductConcretePageSearchTransfersByProductIds(array $productIds): array;

    /**
     * Specification:
     * - Publishes searchable concrete products by given abstract product ids.
     * - Executes `ProductConcreteCollectionFilterPluginInterface` stack of plugins.
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function publishProductConcretePageSearchesByProductAbstractIds(array $productAbstractIds): void;

    /**
     * Specification:
     * - Expands ProductConcretePageSearchTransfer with images data and returns the modified object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    public function expandProductConcretePageSearchTransferWithProductImages(
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
    ): ProductConcretePageSearchTransfer;

    /**
     * Specification:
     * - Expands PageMapTransfer with category map data.
     * - Expands PageMapTransfer with full text search data.
     * - Expands PageMapTransfer with sorting data.
     * - Returns expanded PageMapTransfer.
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
    public function expandProductPageMapWithCategoryData(
        PageMapTransfer $pageMapTransfer,
        PageMapBuilderInterface $pageMapBuilder,
        array $productData,
        LocaleTransfer $localeTransfer
    ): PageMapTransfer;

    /**
     * Specification:
     * - Returns abstract product ids for the given price product store ids.
     * - Returns empty array when price produce ids is empty.
     *
     * @api
     *
     * @param array<int> $priceProductStoreIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByPriceProductStoreIds(array $priceProductStoreIds): array;

    /**
     * Specification:
     * - Expands ProductPageLoadTransfer with price data and returns the modified object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageLoadTransferWithPriceData(
        ProductPageLoadTransfer $productPageLoadTransfer
    ): ProductPageLoadTransfer;

    /**
     * Specification:
     * - Returns an array of SynchronizationDataTransfer filtered by provided productIds.
     * - Uses FilterTransfer for pagination.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransfersByFilterAndProductIds(
        FilterTransfer $filterTransfer,
        array $productIds = []
    ): array;

    /**
     * Specification:
     * - Updates `spy_product_abstract_page_search` by product abstract IDs provided by plugins `ProductPageRefreshPluginInterface`. If the list of IDs is empty nothing will be updated.
     * - Stores the data as json encoded to storage table.
     *
     * @api
     *
     * @return void
     */
    public function refreshProductAbstractPage(): void;

    /**
     * Specification:
     * - Extracts product search IDs from the $eventTransfers.
     * - Finds all product IDs related to product search IDs.
     * - Finds concrete products by product IDs.
     * - Executes a stack of {@link \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcreteCollectionFilterPluginInterface} plugins.
     * - Publishes searchable concrete products.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductConcretePageSearchCollectionByProductEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Publishes product abstract page data by `SpyProductImageSetToProductImage` entity events.
     * - Extracts product image set IDs from the `$eventEntityTransfers` created by product image set to product image entity events.
     * - Finds product abstract IDs by product image set IDs.
     * - Collects product abstract page data.
     * - Stores data in search table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductAbstractPageSearchCollectionByProductImageSetToProductImageEvents(array $eventEntityTransfers): void;
}
