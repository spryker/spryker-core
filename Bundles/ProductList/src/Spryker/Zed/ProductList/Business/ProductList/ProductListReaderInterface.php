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
     * @param int $idProductConcrete
     *
     * @return int[]
     */
    public function getProductBlacklistIdsByIdProductConcrete(int $idProductConcrete): array;

    /**
     * @param int $idProductConcrete
     *
     * @return int[]
     */
    public function getProductWhitelistIdsByIdProductConcrete(int $idProductConcrete): array;

    /**
     * @param int $idProductConcrete
     * @param int[] $blackListIds
     *
     * @return bool
     */
    public function isConcreteProductBlacklisted(int $idProductConcrete, array $blackListIds): bool;

    /**
     * @param int $idProductConcrete
     * @param int[] $whiteListIds
     *
     * @return bool
     */
    public function isConcreteProductWhitelisted(int $idProductConcrete, array $whiteListIds): bool;

    /**
     * @param int[] $productListIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductListIds(array $productListIds): array;
}
