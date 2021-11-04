<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Persistence;

use Generated\Shared\Transfer\CategoryTransfer;

interface CategoryGuiRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return bool
     */
    public function isCategoryKeyUsed(CategoryTransfer $categoryTransfer): bool;

    /**
     * @return array<string>
     */
    public function getIndexedCategoryTemplateNames(): array;

    /**
     * @param int $idParentNode
     * @param int $idLocale
     *
     * @return array<string>
     */
    public function getChildrenCategoryNodeNames(int $idParentNode, int $idLocale): array;

    /**
     * @param array<int> $categoryIds
     *
     * @return array<array<string>>
     */
    public function getCategoryStoreNamesGroupedByIdCategory(array $categoryIds): array;
}
