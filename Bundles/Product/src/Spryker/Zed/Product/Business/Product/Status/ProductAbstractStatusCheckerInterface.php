<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Status;

interface ProductAbstractStatusCheckerInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isActive($idProductAbstract);

    /**
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    public function filterActiveIds(array $productAbstractIds): array;
}
