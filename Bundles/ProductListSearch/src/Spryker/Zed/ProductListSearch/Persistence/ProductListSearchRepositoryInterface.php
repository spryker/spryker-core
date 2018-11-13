<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Persistence;

interface ProductListSearchRepositoryInterface
{
    /**
     * @return int
     */
    public function getValueForWhitelistType(): int;

    /**
     * @return int
     */
    public function getValueForBlacklistType(): int;

    /**
     * @param int[] $concreteIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByConcreteIds(array $concreteIds): array;

    /**
     * @param array $categoryIds
     *
     * @return array
     */
    public function getProductAbstractIdsByCategoryIds(array $categoryIds): array;

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
    public function getProductList(array $productAbstractIds): array;
}
