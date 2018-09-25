<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Persistence;

interface ProductCategoryRepositoryInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function findCategoryIdsByIdProductAbstract(int $idProductAbstract): array;
}
