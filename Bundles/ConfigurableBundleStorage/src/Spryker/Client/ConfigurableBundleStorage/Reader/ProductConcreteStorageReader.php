<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleStorage\Reader;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToProductStorageClientInterface;

class ProductConcreteStorageReader implements ProductConcreteStorageReaderInterface
{
    protected const MAPPING_TYPE_SKU = ProductConcreteTransfer::SKU;

    /**
     * @var \Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToProductStorageClientInterface $productStorageClient
     */
    public function __construct(ConfigurableBundleStorageToProductStorageClientInterface $productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param string[] $skus
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getProductConcreteStoragesBySkusForCurrentLocale(array $skus): array
    {
        $productConcreteTransfers = [];

        foreach ($skus as $sku) {
            $productConcreteTransfer = $this->findProductConcreteTransfer($sku);

            if ($productConcreteTransfer) {
                $productConcreteTransfers[$sku] = $productConcreteTransfer;
            }
        }

        return $productConcreteTransfers;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    protected function findProductConcreteTransfer(string $sku): ?ProductConcreteTransfer
    {
        $productConcreteData = $this->productStorageClient->findProductConcreteStorageDataByMappingForCurrentLocale(static::MAPPING_TYPE_SKU, $sku);

        if (!$productConcreteData) {
            return null;
        }

        return $this->productStorageClient->mapProductStorageDataToProductConcreteTransfer($productConcreteData);
    }
}
