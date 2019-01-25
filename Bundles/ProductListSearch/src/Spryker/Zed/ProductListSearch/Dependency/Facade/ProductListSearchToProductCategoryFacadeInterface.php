<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Dependency\Facade;

interface ProductListSearchToProductCategoryFacadeInterface
{
    /**
     * Specification:
     *  - Finds list of concrete product ids by category ids.
     *
     * @api
     *
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function getProductConcreteIdsByCategoryIds(array $categoryIds): array;
}
