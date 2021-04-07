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
     *
     * @return bool
     */
    public function isCategoryKeyUsed(string $categoryKey): bool;

    /**
     * @return string[]
     */
    public function getIndexedCategoryTemplateNames(): array;

    /**
     * @param int $idParentNode
     * @param int $idLocale
     *
     * @return string[]
     */
    public function getChildrenCategoryNodeNames(int $idParentNode, int $idLocale): array;
}
