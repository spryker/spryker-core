<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategorySearch\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ProductCategorySearchRepositoryInterface
{
    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Orm\Zed\ProductCategory\Persistence\SpyProductCategory[]>
     */
    public function getMappedProductCategoriesByIdProductAbstractAndStore(array $productAbstractIds): array;

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<int>
     */
    public function getCategoryNodeIdsByLocaleAndStore(LocaleTransfer $localeTransfer, StoreTransfer $storeTransfer): array;

    /**
     * @return array
     */
    public function getAllCategoriesWithAttributesAndOrderByDescendant(): array;

    /**
     * @param array<int> $categoryNodeIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getCategoryAttributesByLocale(array $categoryNodeIds, LocaleTransfer $localeTransfer): array;
}
