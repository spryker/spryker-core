<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Communication\ProductAlternativeStorageMapper;

interface ProductAlternativeStorageMapperInterface
{
    /**
     * @param \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage[] $productAlternativeStorageEntities
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapProductAlternativeStorageEntitiesToSynchronizationDataTransfers(array $productAlternativeStorageEntities): array;

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeStorageTransfer[] $productAlternativeStorageTransfers
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapProductAlternativeStorageTransfersToSynchronizationDataTransfers(array $productAlternativeStorageTransfers): array;
}
