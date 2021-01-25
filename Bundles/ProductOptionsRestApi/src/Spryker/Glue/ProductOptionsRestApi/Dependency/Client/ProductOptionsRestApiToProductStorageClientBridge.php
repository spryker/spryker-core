<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Dependency\Client;

class ProductOptionsRestApiToProductStorageClientBridge implements ProductOptionsRestApiToProductStorageClientInterface
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
     * @return array
     */
    public function getBulkProductAbstractIdsByMapping(
        string $mappingType,
        array $identifiers,
        string $localeName
    ): array {
        return $this->productStorageClient->getBulkProductAbstractIdsByMapping($mappingType, $identifiers, $localeName);
    }

    /**
     * @param string $mappingType
     * @param string[] $identifiers
     * @param string $localeName
     *
     * @return array
     */
    public function getBulkProductConcreteStorageDataByMapping(
        string $mappingType,
        array $identifiers,
        string $localeName
    ): array {
        return $this->productStorageClient->getBulkProductConcreteStorageDataByMapping(
            $mappingType,
            $identifiers,
            $localeName
        );
    }

    /**
     * @param string $mappingType
     * @param string $identifier
     *
     * @return array|null
     */
    public function findProductConcreteStorageDataByMappingForCurrentLocale(
        string $mappingType,
        string $identifier
    ): ?array {
        return $this->productStorageClient->findProductConcreteStorageDataByMappingForCurrentLocale(
            $mappingType,
            $identifier
        );
    }
}
