<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Finder;

use Generated\Shared\Transfer\CategoryTransfer;

interface CategoryFinderInterface
{
    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategoryWithLocalizedAttributesById(int $idCategory): ?CategoryTransfer;

    /**
     * @param int|null $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getCategoryNodes(?int $idCategory = null): array;
}
