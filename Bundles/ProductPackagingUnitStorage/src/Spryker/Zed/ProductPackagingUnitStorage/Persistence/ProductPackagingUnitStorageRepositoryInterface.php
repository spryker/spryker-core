<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductPackagingUnitStorageRepositoryInterface
{
    /**
     * @deprecated Use getProductAbstractPackagingStorageEntitiesByFilter instead.
     *
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function findProductAbstractPackagingStorageEntitiesByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    public function findPackagingProductsByProductAbstractId(int $idProductAbstract): array;

    /**
     * @deprecated Use getAllProductAbstractPackagingStorageByFilter instead.
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function findAllProductAbstractPackagingStorageEntities(): array;

    /**
     * @deprecated Use getProductPackagingLeadProductByFilter instead.
     *
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingLeadProductEntityTransfer[]
     */
    public function getProductPackagingLeadProductEntityTransfersByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function findFilteredProductAbstractPackagingUnitStorages(FilterTransfer $filterTransfer, array $productAbstractIds = []): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingLeadProductEntityTransfer[]
     */
    public function getProductPackagingLeadProductByFilter(FilterTransfer $filterTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function getAllProductAbstractPackagingStorageByFilter(FilterTransfer $filterTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function getProductAbstractPackagingStorageEntitiesByFilter(FilterTransfer $filterTransfer, array $productAbstractIds): array;
}
