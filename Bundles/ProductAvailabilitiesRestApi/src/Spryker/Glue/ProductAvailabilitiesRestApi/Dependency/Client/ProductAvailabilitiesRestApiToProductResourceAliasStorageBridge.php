<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client;

class ProductAvailabilitiesRestApiToProductResourceAliasStorageBridge implements ProductAvailabilitiesRestApiToProductResourceAliasStorageInterface
{
    /**
     * @var \Spryker\Client\ProductResourceAliasStorage\ProductResourceAliasStorageClientInterface
     */
    protected $productResourceAliasStorageClient;

    /**
     * ProductsAvailabilityRestApiToProductStorageBridge constructor.
     *
     * @param \Spryker\Client\ProductResourceAliasStorage\ProductResourceAliasStorageClientInterface $productRestApiStorageClient
     */
    public function __construct($productRestApiStorageClient)
    {
        $this->productResourceAliasStorageClient = $productRestApiStorageClient;
    }

    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductConcreteStorageDataBySku(string $sku, string $localeName): ?array
    {
        return $this->productResourceAliasStorageClient->findProductConcreteStorageDataBySku($sku, $localeName);
    }
}
