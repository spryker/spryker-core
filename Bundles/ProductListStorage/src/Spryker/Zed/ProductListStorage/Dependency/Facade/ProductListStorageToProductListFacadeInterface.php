<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Dependency\Facade;

interface ProductListStorageToProductListFacadeInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductBlacklistIdsIdProductAbstract(int $idProductAbstract): array;

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
     * @param int[] $productListIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductListIds(array $productListIds): array;
}
