<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationPersistentCart\Dependency\Client;

class ProductConfigurationPersistentCartToProductConfigurationStorageClientBridge implements ProductConfigurationPersistentCartToProductConfigurationStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageClientInterface
     */
    protected $productConfigurationStorageClient;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageClientInterface $productConfigurationStorageClient
     */
    public function __construct($productConfigurationStorageClient)
    {
        $this->productConfigurationStorageClient = $productConfigurationStorageClient;
    }

    /**
     * @param array<string> $skus
     *
     * @return array<\Generated\Shared\Transfer\ProductConfigurationInstanceTransfer>
     */
    public function findProductConfigurationInstancesIndexedBySku(array $skus): array
    {
        return $this->productConfigurationStorageClient->findProductConfigurationInstancesIndexedBySku($skus);
    }
}
