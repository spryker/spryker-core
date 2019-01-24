<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Dependency\Facade;

interface ProductListToProductFacadeInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    public function getProductConcreteIdsByAbstractProductIds(array $productAbstractIds): array;
}
