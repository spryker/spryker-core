<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Business;

interface ProductListSearchFacadeInterface
{
    /**
     * Specification:
     *  - Retrieve list of abstract product ids by concrete product ids.
     *
     * @api
     *
     * @param int[] $productConcreteIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByConcreteIds(array $productConcreteIds): array;

    /**
     * Specification:
     *  - Retrieve list of abstract product ids by category ids.
     *
     * @api
     *
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByCategoryIds(array $categoryIds): array;
}
