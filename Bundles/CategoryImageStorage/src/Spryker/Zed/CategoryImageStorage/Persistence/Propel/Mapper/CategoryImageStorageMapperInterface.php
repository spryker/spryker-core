<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;

interface CategoryImageStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer $categoryImageStorageEntityTransferTransfer
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $synchronizationDataTransfer
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    public function mapCategoryImageStorageEntityTransferToSynchronizationDataTransfer(
        SpyCategoryImageStorageEntityTransfer $categoryImageStorageEntityTransferTransfer,
        SynchronizationDataTransfer $synchronizationDataTransfer
    ): SynchronizationDataTransfer;
}
