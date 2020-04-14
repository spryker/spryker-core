<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Dependency\Client;

class ProductReviewsRestApiToProductStorageClientBridge implements ProductReviewsRestApiToProductStorageClientInterface
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
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageDataByMapping(string $mappingType, string $identifier, string $localeName): ?array
    {
        return $this->productStorageClient->findProductAbstractStorageDataByMapping($mappingType, $identifier, $localeName);
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
}
