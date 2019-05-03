<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Communication\ProductPackagingUnitStorageMapper;

interface ProductPackagingUnitStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[] $productAbstractPackagingStorageEntities
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapProductAbstractPackagingStorageEntitiesToSynchronizationDataTransfers(array $productAbstractPackagingStorageEntities): array;
}
