<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Business\ProductAbstract;

interface ProductAbstractReaderInterface
{
    /**
     * @param int[] $concreteIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByConcreteIds(array $concreteIds): array;

    /**
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByCategoryIds(array $categoryIds): array;
}
