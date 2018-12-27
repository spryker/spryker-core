<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Communication\ProductDiscontinueStorageMapper;

interface ProductDiscontinuedStorageMapperInterface
{
    /**
     * @param \Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage[] $productDiscontinuedStorageEntities
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapProductDiscontinuedStorageEntitiesToSynchronizationDataTransfers(array $productDiscontinuedStorageEntities): array;
}
