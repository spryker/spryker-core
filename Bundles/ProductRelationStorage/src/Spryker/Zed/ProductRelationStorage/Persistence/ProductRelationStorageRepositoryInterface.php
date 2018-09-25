<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Persistence;

interface ProductRelationStorageRepositoryInterface
{
    /**
     * @param int[] $relationIds
     *
     * @return array
     */
    public function getProductRelationsWithProductAbstractByIdRelationIn(array $relationIds): array;
}
