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
     * @return array<int>
     */
    public function getRelatedCategoryIdsByIdProductList(int $idProductList): array;

    /**
     * @param int $idProductList
     *
     * @return array<int>
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
     * @return array<int>
     */
    public function getProductBlacklistIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * @param int $idProductAbstract
     *
     * @return array<int>
     */
    public function getAbstractProductWhitelistIds(int $idProductAbstract): array;

    /**
     * @param int $idProductAbstract
     *
     * @return array<int>
     */
    public function getCategoryWhitelistIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * @param array<string> $productConcreteSkus
     * @param array<int> $blackListIds
     *
     * @return array<string>
     */
    public function getProductConcreteSkusInBlacklists(array $productConcreteSkus, array $blackListIds): array;

    /**
     * @param array<string> $productConcreteSkus
     * @param array<int> $whiteListIds
     *
     * @return array<string>
     */
    public function getProductConcreteSkusInWhitelists(array $productConcreteSkus, array $whiteListIds): array;

    /**
     * @param int $idProduct
     * @param string $listType
     *
     * @return array<int>
     */
    public function getProductConcreteProductListIdsForType(int $idProduct, string $listType): array;

    /**
     * @param int $idProduct
     * @param string $listType
     *
     * @return array<int>
     */
    public function getProductConcreteProductListIdsRelatedToCategoriesForType(int $idProduct, string $listType): array;

    /**
     * @module Product
     *
     * @param array<int> $productListIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsRelatedToProductConcrete(array $productListIds): array;

    /**
     * @param array<int> $productListIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsRelatedToCategories(array $productListIds): array;

    /**
     * @param array<int> $productListIds
     *
     * @return array<int>
     */
    public function getProductConcreteIdsRelatedToProductLists(array $productListIds): array;

    /**
     * @param array<int> $productIds
     *
     * @return array
     */
    public function getProductListIdsByProductIds(array $productIds): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array
     */
    public function getProductListByProductAbstractIdsThroughCategory(array $productAbstractIds): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array
     */
    public function getProductBlacklistsByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array
     */
    public function getProductWhiteListsByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param array<int> $productListIds
     *
     * @return array<int>
     */
    public function getProductConcreteIdsRelatedToProductListsCategories(array $productListIds): array;
}
