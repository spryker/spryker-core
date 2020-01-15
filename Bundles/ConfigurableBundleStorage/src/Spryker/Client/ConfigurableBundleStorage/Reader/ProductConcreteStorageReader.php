<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleStorage\Reader;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
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
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getProductConcretesBySkusAndLocale(array $skus, string $localeName): array
    {
        $productViewTransfers = [];

        foreach ($skus as $sku) {
            $productViewTransfer = $this->findProductViewTransfer($sku, $localeName);

            if ($productViewTransfer) {
                $productViewTransfers[$sku] = $productViewTransfer;
            }
        }

        return $productViewTransfers;
    }

    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    protected function findProductViewTransfer(string $sku, string $localeName): ?ProductViewTransfer
    {
        $productStorageConcreteData = $this->productStorageClient->findProductConcreteStorageDataByMapping(static::MAPPING_TYPE_SKU, $sku, $localeName);

        if (!$productStorageConcreteData) {
            return null;
        }

        return (new ProductViewTransfer())->fromArray($productStorageConcreteData, true);
    }
}
