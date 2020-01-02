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
     * @param \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer[] $productAlternativeStorageEntityTransfers
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapProductAlternativeStorageEntityTransfersToSynchronizationDataTransfers(array $productAlternativeStorageEntityTransfers): array;
}
