<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryResourceAliasStorage\Persistence;

interface ProductCategoryResourceAliasStorageRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage[]
     */
    public function getProductAbstractCategoryStorageEntities(array $productAbstractIds): array;

    /**
     * @param int[] $productCategoryIds
     *
     * @return array
     */
    public function getProductAbstractCategorysSkuList(array $productCategoryIds): array;
}
