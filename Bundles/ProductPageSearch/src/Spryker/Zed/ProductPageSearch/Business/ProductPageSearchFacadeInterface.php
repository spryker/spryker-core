<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business;

use Generated\Shared\Transfer\FilterTransfer;

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
     * @param array $productAbstractIds
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
     * @param array $productAbstractIds
     * @param array $pageDataExpanderPluginNames
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
     * @param array $productAbstractIds
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
     * Specification
     * - Returns array of ProductEntityTransfer objects.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    public function getProductEntityByFilter(FilterTransfer $filterTransfer): array;
}
