<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Dependency\Facade;

interface ProductRelationStorageToProductRelationFacadeInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer[]
     */
    public function getProductRelationsByIdProductAbstracts(array $productAbstractIds): array;
}
