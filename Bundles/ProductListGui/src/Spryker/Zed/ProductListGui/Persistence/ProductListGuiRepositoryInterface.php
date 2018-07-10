<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Persistence;

interface ProductListGuiRepositoryInterface
{
    /**
     * @return array
     */
    public function getCategoriesWithPaths(): array;

    /**
     * @param string[] $sku
     *
     * @return int[]
     */
    public function findProductIdsByProductConcreteSku(array $sku): array;

    /**
     * @param int[] $productIds
     *
     * @return string[]
     */
    public function findProductSkuByIdProductConcrete(array $productIds): array;

    /**
     * @param int[] $productIds
     *
     * @return array
     */
    public function findProductConcreteDataByIds(array $productIds): array;
}
