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
    public function getProductBlacklistIdsByIdProductAbstract(int $idProductAbstract): array;

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
     * @param int $idProduct
     *
     * @return int[]
     */
    public function getConcreteProductBlacklistIds(int $idProduct): array;

    /**
     * @param int $idProduct
     *
     * @return int[]
     */
    public function getConcreteProductWhitelistIds(int $idProduct): array;

    /**
     * @param string[] $productConcreteSkus
     * @param int[] $blackListIds
     *
     * @return string[]
     */
    public function getProductConcreteSkusInBlacklists(array $productConcreteSkus, array $blackListIds): array;

    /**
     * @param string[] $productConcreteSkus
     * @param int[] $whiteListIds
     *
     * @return string[]
     */
    public function getProductConcreteSkusInWhitelists(array $productConcreteSkus, array $whiteListIds): array;

    /**
     * @param int[] $productListIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductListIds(array $productListIds): array;
}
