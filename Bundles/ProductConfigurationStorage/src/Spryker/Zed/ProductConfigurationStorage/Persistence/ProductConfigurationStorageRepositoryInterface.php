<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Persistence;

use Generated\Shared\Transfer\ProductConfigurationFilterTransfer;

interface ProductConfigurationStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findProductConfigurationStorageDataTransfersByIds(
        ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
    ): array;
}
