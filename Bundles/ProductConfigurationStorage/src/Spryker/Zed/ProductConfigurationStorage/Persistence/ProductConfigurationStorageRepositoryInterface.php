<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductConfigurationStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productConfigurationStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getFilteredProductConfigurationStorageDataTransfers(
        FilterTransfer $filterTransfer,
        array $productConfigurationStorageIds
    ): array;
}
