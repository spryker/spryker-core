<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Dependency\Client;

class ProductConfigurationStorageToProductStorageClientBridge implements ProductConfigurationStorageToProductStorageClientInterface
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
     * @param int $idProductConcrete
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductConcreteStorageData(int $idProductConcrete, string $localeName): ?array
    {
        return $this->productStorageClient->findProductConcreteStorageData($idProductConcrete, $localeName);
    }
}
