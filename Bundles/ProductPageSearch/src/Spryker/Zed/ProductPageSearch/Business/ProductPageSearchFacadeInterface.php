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
     * - Queries all productAbstract with the given productAbstractIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param int[] $productAbstractIds
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
     *
     * @api
     *
     * @param int[] $productAbstractIds
     * @param string[] $pageDataExpanderPluginNames
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
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds);

    /**
     * Specification:
     * - Publishes concrete products with given ids.
     *
     * @api
     *
     * @param int[] $productIds
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
     * @param int[] $productIds
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
     * Specification
     * - Finds product concrete page search entities by given concrete product ids.
     * - Returns array of ProductConcretePageSearchTransfer objects.
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    public function getProductConcretePageSearchTransfersByProductIds(array $productIds): array;

    /**
     * Specification:
     * - Publishes concrete products by given abstract product ids.
     *
     * @api
     *
     * @param int[] $productAbstractIds
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
     * @param array $productData
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
     * @param int[] $priceProductStoreIds
     *
     * @return int[]
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
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getSynchronizationDataTransfersByFilterAndProductIds(
        FilterTransfer $filterTransfer,
        array $productIds = []
    ): array;
}
