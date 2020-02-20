<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Persistence;

interface SalesQuantityRepositoryInterface
{
    /**
     * @param string $productConcreteSku
     *
     * @return bool
     */
    public function isProductQuantitySplittable(string $productConcreteSku): bool;

    public function getIsProductQuantitySplittableByProductConcreteSkus(array $productConcreteSkus): array;
}
