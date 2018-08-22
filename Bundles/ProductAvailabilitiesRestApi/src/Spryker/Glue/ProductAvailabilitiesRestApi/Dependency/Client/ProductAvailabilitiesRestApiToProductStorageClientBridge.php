<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client;

class ProductAvailabilitiesRestApiToProductStorageClientBridge implements ProductAvailabilitiesRestApiToProductStorageClientInterface
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
     * @param string $mapping
     * @param string $identifier
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageDataByMap(string $mapping, string $identifier, string $localeName): ?array
    {
        return $this->productStorageClient->findProductAbstractStorageDataByMap($mapping, $identifier, $localeName);
    }

    /**
     * @param string $mapping
     * @param string $identifier
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductConcreteStorageDataByMap(string $mapping, string $identifier, string $localeName): ?array
    {
        return $this->productStorageClient->findProductConcreteStorageDataByMap($mapping, $identifier, $localeName);
    }
}
