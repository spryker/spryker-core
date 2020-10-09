<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Dependency\Client;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;

class ProductConfigurationStorageToProductConfigurationClientBridge implements ProductConfigurationStorageToProductConfigurationClientInterface
{
    /**
     * @var \Spryker\Client\ProductConfiguration\ProductConfigurationClientInterface
     */
    protected $productConfigurationClient;

    /**
     * @param \Spryker\Client\ProductConfiguration\ProductConfigurationClientInterface $productConfigurationClient
     */
    public function __construct($productConfigurationClient)
    {
        $this->productConfigurationClient = $productConfigurationClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     * @param array $productData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function validateProductConfiguratorCheckSumResponse(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer,
        array $productData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        return $this->productConfigurationClient->validateProductConfiguratorCheckSumResponse(
            $productConfiguratorResponseProcessorResponseTransfer,
            $productData
        );
    }
}
