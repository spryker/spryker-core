<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence;

interface ProductAlternativeStorageRepositoryInterface
{
    /**
     * @param int[] $productAlternativeIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer[]
     */
    public function findProductAlternativeStorageEntities(array $productAlternativeIds): array;
}
