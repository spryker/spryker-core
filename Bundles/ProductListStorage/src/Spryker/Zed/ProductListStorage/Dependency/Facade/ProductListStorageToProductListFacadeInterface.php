<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Dependency\Facade;

use Generated\Shared\Transfer\ProductListCollectionTransfer;
use Generated\Shared\Transfer\ProductListCriteriaTransfer;

interface ProductListStorageToProductListFacadeInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return array<int>
     */
    public function getProductBlacklistIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * @param int $idProductAbstract
     *
     * @return array<int>
     */
    public function getProductWhitelistIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * @param int $idProductAbstract
     *
     * @return array<int>
     */
    public function getCategoryWhitelistIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * @param int $idProduct
     *
     * @return array<int>
     */
    public function getProductBlacklistIdsByIdProduct(int $idProduct): array;

    /**
     * @param int $idProduct
     *
     * @return array<int>
     */
    public function getProductWhitelistIdsByIdProduct(int $idProduct): array;

    /**
     * @param array<int> $productListIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByProductListIds(array $productListIds): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array
     */
    public function getProductAbstractListIdsByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array
     */
    public function getProductListsIdsByProductIds(array $productConcreteIds): array;

    /**
     * @param \Generated\Shared\Transfer\ProductListCriteriaTransfer $productListCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListCollectionTransfer
     */
    public function getProductListCollection(ProductListCriteriaTransfer $productListCriteriaTransfer): ProductListCollectionTransfer;
}
