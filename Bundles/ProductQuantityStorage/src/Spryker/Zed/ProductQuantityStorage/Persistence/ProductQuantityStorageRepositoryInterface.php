<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Persistence;

interface ProductQuantityStorageRepositoryInterface
{
    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer[]
     */
    public function findProductQuantityStorageEntitiesByProductIds(array $productIds): array;
}
