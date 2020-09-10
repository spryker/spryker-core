<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Reader;

use Generated\Shared\Transfer\ProductConcreteStorageTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToProductStorageClientInterface;

class ProductConcreteStorageReader implements ProductConcreteStorageReaderInterface
{
    /** @see /Spryker/Zed/ProductConfigurationStorage/Persistence/Propel/Schema/spy_product_configuration_storage.schema.xml */
    protected const MAPPING_TYPE_SKU = 'sku';

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToProductStorageClientInterface $productStorageClient
     */
    public function __construct(ProductConfigurationStorageToProductStorageClientInterface $productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteStorageTransfer|null
     */
    public function findProductConcreteStorageBySku(string $sku): ?ProductConcreteStorageTransfer
    {
        $productConcreteStorageData = $this->productStorageClient->findProductConcreteStorageDataByMappingForCurrentLocale(
            static::MAPPING_TYPE_SKU,
            $sku
        );

        return $this->mapProductConcreteStorageDataToProductConcreteStorageTransfer(
            $productConcreteStorageData,
            new ProductConcreteStorageTransfer()
        );
    }

    /**
     * @param array $productConcreteStorageData
     * @param \Generated\Shared\Transfer\ProductConcreteStorageTransfer $productConcreteStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteStorageTransfer
     */
    protected function mapProductConcreteStorageDataToProductConcreteStorageTransfer(
        array $productConcreteStorageData,
        ProductConcreteStorageTransfer $productConcreteStorageTransfer
    ): ProductConcreteStorageTransfer {
        return $productConcreteStorageTransfer->fromArray($productConcreteStorageData, true);
    }
}
