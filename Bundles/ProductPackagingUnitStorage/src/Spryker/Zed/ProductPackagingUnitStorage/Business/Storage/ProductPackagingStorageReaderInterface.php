<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business\Storage;

interface ProductPackagingStorageReaderInterface
{
    /**
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer[]
     */
    public function getProductConcretePackagingStorageTransfer(array $productConcreteIds): array;

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SpyProductConcretePackagingStorageEntityTransfer[]
     */
    public function getProductConcretePackagingStorageEntities(array $productConcreteIds): array;
}
