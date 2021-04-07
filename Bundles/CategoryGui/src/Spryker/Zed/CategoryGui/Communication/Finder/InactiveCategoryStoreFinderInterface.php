<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Finder;

interface InactiveCategoryStoreFinderInterface
{
    /**
     * @param int|null $idCategoryNode
     *
     * @return int[]
     */
    public function findStoresByIdCategoryNode(?int $idCategoryNode): array;
}
