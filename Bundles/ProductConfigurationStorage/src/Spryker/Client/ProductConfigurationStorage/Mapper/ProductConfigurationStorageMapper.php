<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Mapper;

use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;

class ProductConfigurationStorageMapper implements ProductConfigurationStorageMapperInterface
{
    /**
     * @param array $productConfigurationStorageData
     * @param \Generated\Shared\Transfer\ProductConfigurationStorageTransfer $productConfigurationStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationStorageTransfer
     */
    public function mapProductConfigurationStorageDataToProductConfigurationStorageTransfer(
        array $productConfigurationStorageData,
        ProductConfigurationStorageTransfer $productConfigurationStorageTransfer
    ): ProductConfigurationStorageTransfer {
        return $productConfigurationStorageTransfer->fromArray($productConfigurationStorageData, true);
    }

    /**
     * @param array $productConfigurationStoragesData
     *
     * @return array<\Generated\Shared\Transfer\ProductConfigurationStorageTransfer>
     */
    public function mapProductConfigurationStoragesDataToProductConfigurationStorageTransfers(array $productConfigurationStoragesData): array
    {
        $productConfigurationStorageTransfers = [];
        foreach ($productConfigurationStoragesData as $productConfigurationStorageData) {
            if ($productConfigurationStorageData === null) {
                continue;
            }

            if (is_string($productConfigurationStorageData)) {
                $productConfigurationStorageData = json_decode($productConfigurationStorageData, true);
            }

            $productConfigurationStorageTransfers[] = $this->mapProductConfigurationStorageDataToProductConfigurationStorageTransfer(
                $productConfigurationStorageData,
                new ProductConfigurationStorageTransfer()
            );
        }

        return $productConfigurationStorageTransfers;
    }
}
