<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategorySearch\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductCategorySearchRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategory[][]
     */
    public function getMappedProductCategoriesByIdProductAbstractAndStore(array $productAbstractIds): array;

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return int[]
     */
    public function getCategoryNodeIdsByLocale(LocaleTransfer $localeTransfer): array;

    /**
     * @return array
     */
    public function getAllCategoriesWithAttributesAndOrderByDescendant(): array;

    /**
     * @param int[] $categoryIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getCategoryAttributesByLocale(array $categoryIds, LocaleTransfer $localeTransfer): array;
}
