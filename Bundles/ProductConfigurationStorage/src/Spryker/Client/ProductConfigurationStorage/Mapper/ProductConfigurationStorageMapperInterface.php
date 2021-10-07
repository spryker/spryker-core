<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Mapper;

use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;

interface ProductConfigurationStorageMapperInterface
{
    /**
     * @param array $productConfigurationStorageData
     * @param \Generated\Shared\Transfer\ProductConfigurationStorageTransfer $configurationStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationStorageTransfer
     */
    public function mapProductConfigurationStorageDataToProductConfigurationStorageTransfer(
        array $productConfigurationStorageData,
        ProductConfigurationStorageTransfer $configurationStorageTransfer
    ): ProductConfigurationStorageTransfer;

    /**
     * @param array $productConfigurationStoragesData
     *
     * @return array<\Generated\Shared\Transfer\ProductConfigurationStorageTransfer>
     */
    public function mapProductConfigurationStoragesDataToProductConfigurationStorageTransfers(array $productConfigurationStoragesData): array;
}
