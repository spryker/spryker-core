<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Dependency\Client;

class ProductsRestApiToProductStorageClientBridge implements ProductsRestApiToProductStorageClientInterface
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

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageData(int $idProductAbstract, string $localeName): ?array
    {
        return $this->productStorageClient->findProductAbstractStorageData($idProductAbstract, $localeName);
    }

    /**
     * @param string $mappingType
     * @param string $identifier
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageDataByMapping(string $mappingType, string $identifier, string $localeName): ?array
    {
        return $this->productStorageClient->findProductAbstractStorageDataByMapping($mappingType, $identifier, $localeName);
    }

    /**
     * @param string $mappingType
     * @param string $identifier
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductConcreteStorageDataByMapping(string $mappingType, string $identifier, string $localeName): ?array
    {
        return $this->productStorageClient->findProductConcreteStorageDataByMapping($mappingType, $identifier, $localeName);
    }

    /**
     * @param string $mappingType
     * @param string[] $identifiers
     * @param string $localeName
     *
     * @return array
     */
    public function findBulkProductAbstractStorageDataByMapping(string $mappingType, array $identifiers, string $localeName): array
    {
        return $this->productStorageClient->findBulkProductAbstractStorageDataByMapping($mappingType, $identifiers, $localeName);
    }

    /**
     * @param int[] $productAbstractIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function getBulkProductAbstractStorageDataByProductAbstractIdsForLocaleNameAndStore(
        array $productAbstractIds,
        string $localeName,
        string $storeName
    ): array {
        return $this->productStorageClient->getBulkProductAbstractStorageDataByProductAbstractIdsForLocaleNameAndStore($productAbstractIds, $localeName, $storeName);
    }

    /**
     * @param int[] $productConcreteIds
     * @param string $localeName
     *
     * @return array
     */
    public function getBulkProductConcreteStorageDataByProductConcreteIdsAndLocaleName(
        array $productConcreteIds,
        string $localeName
    ): array {
        return $this->productStorageClient
            ->getBulkProductConcreteStorageDataByProductConcreteIdsAndLocaleName($productConcreteIds, $localeName);
    }
}
