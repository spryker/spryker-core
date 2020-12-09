<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Persistence;

interface CategoryGuiRepositoryInterface
{
    /**
     * @param string $categoryKey
     * @param int $idCategory
     *
     * @return bool
     */
    public function isCategoryKeyUsed(string $categoryKey, int $idCategory): bool;

    /**
     * @return string[]
     */
    public function getIndexedCategoryTemplateNames(): array;

    /**
     * @param int $idParentNode
     * @param int $idLocale
     *
     * @return array
     */
    public function getChildrenCategoryNodeNames(int $idParentNode, int $idLocale): array;

    /**
     * @param int[] $categoryNodeIds
     *
     * @return \Generated\Shared\Transfer\UrlTransfer[]
     */
    public function getCategoryNodeUrls(array $categoryNodeIds): array;
}
