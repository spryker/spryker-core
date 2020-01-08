<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;

interface ProductAlternativeStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer $productAlternativeStorageEntityTransfer
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $synchronizationDataTransfer
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    public function mapProductAlternativeStorageEntityTransferToSynchronizationDataTransfer(
        SpyProductAlternativeStorageEntityTransfer $productAlternativeStorageEntityTransfer,
        SynchronizationDataTransfer $synchronizationDataTransfer
    ): SynchronizationDataTransfer;
}
