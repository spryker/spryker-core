<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Mapper;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;

class ProductConfigurationInstanceMapper implements ProductConfigurationInstanceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationStorageTransfer $productConfigurationStorageTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    public function mapProductConfigurationStorageTransferToProductConfigurationInstanceTransfer(
        ProductConfigurationStorageTransfer $productConfigurationStorageTransfer,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationInstanceTransfer {
        $productConfigurationInstanceTransfer->fromArray($productConfigurationStorageTransfer->toArray(), true);

        $productConfigurationInstanceTransfer->setConfiguration(
            $productConfigurationStorageTransfer->getDefaultConfiguration(),
        );
        $productConfigurationInstanceTransfer->setDisplayData($productConfigurationStorageTransfer->getDefaultDisplayData());

        return $productConfigurationInstanceTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConfigurationStorageTransfer> $productConfigurationStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConfigurationInstanceTransfer>
     */
    public function mapProductConfigurationStorageTransfersToProductConfigurationInstanceTransfersIndexedBySku(array $productConfigurationStorageTransfers)
    {
        $productConfigurationInstanceTransfers = [];

        foreach ($productConfigurationStorageTransfers as $productConfigurationStorageTransfer) {
            /** @var \Generated\Shared\Transfer\ProductConfigurationStorageTransfer $productConfigurationStorageTransfer */
            $sku = $productConfigurationStorageTransfer->getSku();

            $productConfigurationInstanceTransfers[$sku] = $this->mapProductConfigurationStorageTransferToProductConfigurationInstanceTransfer(
                $productConfigurationStorageTransfer,
                new ProductConfigurationInstanceTransfer(),
            );
        }

        return $productConfigurationInstanceTransfers;
    }
}
