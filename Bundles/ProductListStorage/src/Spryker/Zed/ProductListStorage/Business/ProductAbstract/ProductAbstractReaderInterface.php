<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Business\ProductAbstract;

interface ProductAbstractReaderInterface
{
    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<int>
     */
    public function findProductAbstractIdsByProductConcreteIds(array $productConcreteIds): array;

    /**
     * @param array<int> $categoryIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByCategoryIds(array $categoryIds): array;
}
