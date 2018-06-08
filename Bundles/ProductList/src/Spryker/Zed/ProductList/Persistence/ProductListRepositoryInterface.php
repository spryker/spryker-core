<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Persistence;

interface ProductListRepositoryInterface
{
    /**
     * @param int $idProductList
     *
     * @return array
     */
    public function getRelatedCategoryIdsByIdProductList(int $idProductList): array;

    /**
     * @param int $idProductList
     *
     * @return array
     */
    public function getRelatedProductConcreteIdsByIdProductList(int $idProductList): array;
}
