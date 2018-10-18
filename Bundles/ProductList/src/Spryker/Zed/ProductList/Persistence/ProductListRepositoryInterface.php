<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Persistence;

use Generated\Shared\Transfer\ProductListTransfer;

interface ProductListRepositoryInterface
{
    /**
     * @param int $idProductList
     *
     * @return int[]
     */
    public function getRelatedCategoryIdsByIdProductList(int $idProductList): array;

    /**
     * @param int $idProductList
     *
     * @return int[]
     */
    public function getRelatedProductConcreteIdsByIdProductList(int $idProductList): array;

    /**
     * @param int $idProductList
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function getProductListById(int $idProductList): ProductListTransfer;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasKey(string $key): bool;

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getAbstractProductBlacklistIds(int $idProductAbstract): array;

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getAbstractProductWhitelistIds(int $idProductAbstract): array;

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getCategoryWhitelistIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * @param int $idProductConcrete
     *
     * @return int[]
     */
    public function getConcreteProductBlacklistIds(int $idProductConcrete): array;

    /**
     * @param int $idProductConcrete
     *
     * @return int[]
     */
    public function getConcreteProductWhitelistIds(int $idProductConcrete): array;

    /**
     * @param int[] $productConcreteIds
     *
     * @return int[]
     */
    public function findProductAbstractIdsByProductConcreteIds(array $productConcreteIds): array;

    /**
     * @param int[] $productConcreteIds
     *
     * @return array
     */
    public function getProductListIdsByProductConcreteIdsIn(array $productConcreteIds): array;

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getProductConcreteCountByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getCategoryProductList(array $productAbstractIds): array;

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getProductListsByIdProductAbstractIn(array $productAbstractIds): array;

    /**
     * @param int[] $productListIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductListIds(array $productListIds): array;
}
