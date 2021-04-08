<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Persistence;

interface CategoryGuiRepositoryInterface
{
    /**
     * @module Category
     *
     * @param string $categoryKey
     *
     * @return bool
     */
    public function isCategoryKeyUsed(string $categoryKey): bool;

    /**
     * @module Category
     *
     * @return string[]
     */
    public function getIndexedCategoryTemplateNames(): array;

    /**
     * @module Category
     *
     * @param int $idParentNode
     * @param int $idLocale
     *
     * @return string[]
     */
    public function getChildrenCategoryNodeNames(int $idParentNode, int $idLocale): array;

    /**
     * @module Category
     * @module Store
     *
     * @param int[] $categoryIds
     *
     * @return string[][]
     */
    public function getCategoryStoreNamesGroupedByIdCategory(array $categoryIds): array;
}
