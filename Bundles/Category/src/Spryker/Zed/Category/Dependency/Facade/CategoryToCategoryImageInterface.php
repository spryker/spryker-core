<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Dependency\Facade;

interface CategoryToCategoryImageInterface
{
    /**
     * @param int $idCategory
     *
     * @return array
     */
    public function getCategoryImagesSetCollectionByCategoryId(int $idCategory): array;
}
