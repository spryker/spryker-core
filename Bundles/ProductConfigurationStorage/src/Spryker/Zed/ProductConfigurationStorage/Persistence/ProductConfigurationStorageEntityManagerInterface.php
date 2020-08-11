<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Persistence;

use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;

interface ProductConfigurationStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationStorageTransfer $productConfigurationStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationStorageTransfer
     */
    public function saveProductConfigurationStorage(
        ProductConfigurationStorageTransfer $productConfigurationStorageTransfer
    ): ProductConfigurationStorageTransfer;

    /**
     * @param int[] $productConfigurationIds
     *
     * @return void
     */
    public function deleteProductConfigurationStorageByProductConfigurationIds(array $productConfigurationIds): void;
}
