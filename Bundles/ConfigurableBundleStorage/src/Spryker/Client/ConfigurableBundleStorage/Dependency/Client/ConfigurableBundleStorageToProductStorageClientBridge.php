<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleStorage\Dependency\Client;

use Generated\Shared\Transfer\ProductConcreteTransfer;

class ConfigurableBundleStorageToProductStorageClientBridge implements ConfigurableBundleStorageToProductStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Client\ProductStorage\ProductStorageClientInterface $productStorageClient
     */
    public function __construct($productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param string $mappingType
     * @param string $identifier
     *
     * @return array|null
     */
    public function findProductConcreteStorageDataByMappingForCurrentLocale(string $mappingType, string $identifier): ?array
    {
        return $this->productStorageClient->findProductConcreteStorageDataByMappingForCurrentLocale($mappingType, $identifier);
    }

    /**
     * @param array $productStorageData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapProductStorageDataToProductConcreteTransfer(array $productStorageData): ProductConcreteTransfer
    {
        return $this->productStorageClient->mapProductStorageDataToProductConcreteTransfer($productStorageData);
    }
}
