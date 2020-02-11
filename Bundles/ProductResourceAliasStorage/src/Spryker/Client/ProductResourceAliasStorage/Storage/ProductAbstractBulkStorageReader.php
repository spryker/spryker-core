<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductResourceAliasStorage\Storage;

use Spryker\Client\ProductResourceAliasStorage\Dependency\Client\ProductResourceAliasStorageToProductStorageClientInterface;

class ProductAbstractBulkStorageReader implements ProductAbstractBulkStorageReaderInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';

    /**
     * @var \Spryker\Client\ProductResourceAliasStorage\Dependency\Client\ProductResourceAliasStorageToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Client\ProductResourceAliasStorage\Dependency\Client\ProductResourceAliasStorageToProductStorageClientInterface $productStorageClient
     */
    public function __construct(ProductResourceAliasStorageToProductStorageClientInterface $productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param string[] $identifiers
     * @param string $localeName
     *
     * @return array
     */
    public function getProductAbstractStorageData(array $identifiers, string $localeName): array
    {
        return $this->productStorageClient->findBulkProductAbstractStorageDataByMapping(
            static::PRODUCT_ABSTRACT_MAPPING_TYPE,
            $identifiers,
            $localeName
        );
    }
}
