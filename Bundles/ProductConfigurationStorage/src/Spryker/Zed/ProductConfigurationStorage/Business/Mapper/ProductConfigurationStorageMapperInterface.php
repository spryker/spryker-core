<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Business\Mapper;

use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;
use Generated\Shared\Transfer\ProductConfigurationTransfer;

interface ProductConfigurationStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationTransfer $productConfiguration
     * @param \Generated\Shared\Transfer\ProductConfigurationStorageTransfer $productConfigurationStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationStorageTransfer
     */
    public function mapProductConfigurationTransferToProductConfigurationStorageTransfer(
        ProductConfigurationTransfer $productConfiguration,
        ProductConfigurationStorageTransfer $productConfigurationStorageTransfer
    ): ProductConfigurationStorageTransfer;
}
