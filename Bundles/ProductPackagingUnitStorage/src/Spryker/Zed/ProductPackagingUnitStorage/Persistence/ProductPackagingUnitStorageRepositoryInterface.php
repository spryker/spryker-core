<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

interface ProductPackagingUnitStorageRepositoryInterface
{
    /**
     * @api
     *
     * @param int[] $idProductAbstracts
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function findProductAbstractPackagingUnitStorageByProductAbstractIds(array $idProductAbstracts): array;

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    public function findPackagingProductsByAbstractId(int $idProductAbstract): array;
}
