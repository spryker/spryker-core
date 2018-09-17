<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Persistence;

interface ProductOptionRepositoryInterface
{
    /**
     * @param int $idProductOptionGroup
     * @param bool $isActive
     *
     * @return int[]
     */
    public function findChangedProductOptionGroupProductAbstractIdIndexes(int $idProductOptionGroup, bool $isActive): array;
}
