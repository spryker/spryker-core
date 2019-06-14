<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business\Storage;

interface ProductPackagingStorageReaderInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer[]
     */
    public function getProductAbstractPackagingStorageTransfer(array $productAbstractIds): array;

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[]
     */
    public function getProductAbstractPackagingStorageEntities(array $productAbstractIds): array;
}
