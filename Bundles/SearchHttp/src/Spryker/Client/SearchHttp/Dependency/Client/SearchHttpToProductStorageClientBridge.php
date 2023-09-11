<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Dependency\Client;

class SearchHttpToProductStorageClientBridge implements SearchHttpToProductStorageClientInterface
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
     * @param array<string> $identifiers
     * @param string $localeName
     *
     * @return array<int>
     */
    public function getBulkProductAbstractIdsByMapping(
        string $mappingType,
        array $identifiers,
        string $localeName
    ): array {
        return $this->productStorageClient->getBulkProductAbstractIdsByMapping($mappingType, $identifiers, $localeName);
    }
}
