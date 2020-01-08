<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SpyProductReplacementForStorageEntityTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;

class ProductReplacementStorageMapper implements ProductReplacementStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductReplacementForStorageEntityTransfer $productReplacementStorageEntityTransfer
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $synchronizationDataTransfer
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    public function mapProductReplacementForStorageEntityTransferToSynchronizationDataTransfer(
        SpyProductReplacementForStorageEntityTransfer $productReplacementStorageEntityTransfer,
        SynchronizationDataTransfer $synchronizationDataTransfer
    ): SynchronizationDataTransfer {
        return $synchronizationDataTransfer
            ->setData($productReplacementStorageEntityTransfer->getData())
            ->setKey($productReplacementStorageEntityTransfer->getKey());
    }
}
