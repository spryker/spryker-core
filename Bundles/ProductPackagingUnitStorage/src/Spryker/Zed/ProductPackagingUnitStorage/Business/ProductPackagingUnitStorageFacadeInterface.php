<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductPackagingUnitStorageFacadeInterface
{
    /**
     * Specification:
     * - Saves the provided product abstract IDs related ProductAbstractPackaging objects to storage table.
     * - Sends a copy of data to synchronization queue.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publishProductAbstractPackaging(array $productAbstractIds): void;

    /**
     * Specification:
     * - Finds and deletes ProductPackaging storage entities by productAbstractIds
     * - Sends delete message to synchronization queue.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductAbstractPackaging(array $productAbstractIds): void;

    /**
     * Specification:
     * - Retrieves the list of product abstract IDs which are associated with any of the provided packaging unit type IDs.
     *
     * @api
     *
     * @param int[] $productPackagingUnitTypeIds
     *
     * @return int[]
     */
    public function findProductAbstractIdsByProductPackagingUnitTypeIds(array $productPackagingUnitTypeIds): array;

    /**
     * Specification:
     * - Retrieves ProductAbstractPackagingStorageTransfer collection, associated with provided product abstract IDs.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function getProductAbstractPackagingStorageTransfersByProductAbstractIds(array $productAbstractIds): array;

    /**
     * Specification:
     * - Returns ProductPackagingLeadProductEntityTransfer collection by filter.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingLeadProductEntityTransfer[]
     */
    public function getProductPackagingLeadProductByFilter(FilterTransfer $filterTransfer): array;

    /**
     * Specification:
     * - Returns all ProductReplacementForStorage collection by filter.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function getAllProductAbstractPackagingStorageByFilter(FilterTransfer $filterTransfer): array;

    /**
     * Specification:
     * - Returns ProductReplacementForStorage collection by filter.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function getProductAbstractPackagingStorageEntitiesByFilter(FilterTransfer $filterTransfer, array $productAbstractIds): array;
}
