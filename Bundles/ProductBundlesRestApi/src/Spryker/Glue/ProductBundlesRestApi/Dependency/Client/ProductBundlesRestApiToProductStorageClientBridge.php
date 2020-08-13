<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesRestApi\Dependency\Client;

class ProductBundlesRestApiToProductStorageClientBridge implements ProductBundlesRestApiToProductStorageClientInterface
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
     * @param string[] $identifiers
     * @param string $localeName
     *
     * @return int[]
     */
    public function getProductConcreteIdsByMapping(string $mappingType, array $identifiers, string $localeName): array
    {
        return $this->productStorageClient->getProductConcreteIdsByMapping($mappingType, $identifiers, $localeName);
    }
}
