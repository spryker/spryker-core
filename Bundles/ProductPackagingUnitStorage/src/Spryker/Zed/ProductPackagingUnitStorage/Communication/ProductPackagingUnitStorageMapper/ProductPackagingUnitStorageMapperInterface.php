<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Communication\ProductPackagingUnitStorageMapper;

interface ProductPackagingUnitStorageMapperInterface
{
    /**
     * @param \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorage[] $productAbstractPackagingStorageEntities
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapProductAbstractPackagingStorageEntitiesToSynchronizationDataTransfers(array $productAbstractPackagingStorageEntities): array;
}
