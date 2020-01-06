<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Communication\ProductAlternativeStorageMapper;

interface ProductReplacementForStorageMapperInterface
{
    /**
     * @param \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage[] $productReplacementForStorageEntities
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapProductReplacementForStorageEntitiesToSynchronizationDataTransfers(array $productReplacementForStorageEntities): array;

    /**
     * @param \Generated\Shared\Transfer\ProductReplacementStorageTransfer[] $productReplacementStorageTransfers
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapProductReplacementForStorageEntityTransfersToSynchronizationDataTransfers(array $productReplacementStorageTransfers): array;
}
