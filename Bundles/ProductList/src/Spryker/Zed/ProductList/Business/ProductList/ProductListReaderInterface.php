<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\ProductList;

use Generated\Shared\Transfer\ProductListTransfer;

interface ProductListReaderInterface
{
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
    public function getProductWhitelistIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getCategoryWhitelistIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function getProductListById(ProductListTransfer $productListTransfer): ProductListTransfer;

    /**
     * @param int $idProduct
     *
     * @return int[]
     */
    public function getProductBlacklistIdsByIdProduct(int $idProduct): array;

    /**
     * @param int $idProduct
     *
     * @return int[]
     */
    public function getProductWhitelistIdsByIdProduct(int $idProduct): array;

    /**
     * @param string[] $productConcreteSkus
     * @param int[] $blackListIds
     *
     * @return array
     */
    public function getProductConcreteSkusInBlacklists(array $productConcreteSkus, array $blackListIds): array;

    /**
     * @param string[] $productConcreteSkus
     * @param int[] $whiteListIds
     *
     * @return array
     */
    public function getProductConcreteSkusInWhitelists(array $productConcreteSkus, array $whiteListIds): array;

    /**
     * @param int[] $productListIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductListIds(array $productListIds): array;
}
