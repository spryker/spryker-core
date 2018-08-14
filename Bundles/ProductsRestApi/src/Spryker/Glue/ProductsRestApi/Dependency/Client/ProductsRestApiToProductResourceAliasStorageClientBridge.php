<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Dependency\Client;

class ProductsRestApiToProductResourceAliasStorageClientBridge implements ProductsRestApiToProductResourceAliasStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductResourceAliasStorage\ProductResourceAliasStorageClientInterface
     */
    protected $productResourceAliasStorageClient;

    /**
     * @param \Spryker\Client\ProductResourceAliasStorage\ProductResourceAliasStorageClientInterface $productResourceAliasStorageClient
     */
    public function __construct($productResourceAliasStorageClient)
    {
        $this->productResourceAliasStorageClient = $productResourceAliasStorageClient;
    }

    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageData(string $sku, string $localeName): ?array
    {
        return $this->productResourceAliasStorageClient->findProductAbstractStorageData($sku, $localeName);
    }

    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductConcreteStorageData(string $sku, string $localeName): ?array
    {
        return $this->productResourceAliasStorageClient->getProductConcreteStorageData($sku, $localeName);
    }
}
