<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationPersistentCart\Expander;

use ArrayObject;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Spryker\Client\ProductConfigurationPersistentCart\Dependency\Client\ProductConfigurationPersistentCartToProductConfigurationStorageClientInterface;

class ProductConfigurationInstanceCartChangeExpander implements ProductConfigurationInstanceCartChangeExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationPersistentCart\Dependency\Client\ProductConfigurationPersistentCartToProductConfigurationStorageClientInterface
     */
    protected $productConfigurationStorageClient;

    /**
     * @param \Spryker\Client\ProductConfigurationPersistentCart\Dependency\Client\ProductConfigurationPersistentCartToProductConfigurationStorageClientInterface $productConfigurationStorageClient
     */
    public function __construct(ProductConfigurationPersistentCartToProductConfigurationStorageClientInterface $productConfigurationStorageClient)
    {
        $this->productConfigurationStorageClient = $productConfigurationStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandPersistentCartChangeWithProductConfigurationInstance(
        PersistentCartChangeTransfer $persistentCartChangeTransfer,
        array $params = []
    ): PersistentCartChangeTransfer {
        $skus = $this->extractItemSkus($persistentCartChangeTransfer->getItems());

        if (!$skus) {
            return $persistentCartChangeTransfer;
        }

        $productConfigurationInstancesIndexedBySku = $this->productConfigurationStorageClient->findProductConfigurationInstancesIndexedBySku($skus);

        if (!$productConfigurationInstancesIndexedBySku) {
            return $persistentCartChangeTransfer;
        }

        $expandedItemTransfers = $this->expandItemsWithProductConfigurationInstance(
            $productConfigurationInstancesIndexedBySku,
            $persistentCartChangeTransfer->getItems(),
        );

        $persistentCartChangeTransfer->setItems($expandedItemTransfers);

        return $persistentCartChangeTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConfigurationInstanceTransfer> $productConfigurationInstancesIndexedBySku
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function expandItemsWithProductConfigurationInstance(
        array $productConfigurationInstancesIndexedBySku,
        ArrayObject $itemTransfers
    ): ArrayObject {
        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getProductConfigurationInstance()) {
                continue;
            }

            $productConfigurationInstanceTransfer = $productConfigurationInstancesIndexedBySku[$itemTransfer->getSku()] ?? null;

            if (!$productConfigurationInstanceTransfer) {
                continue;
            }

            $itemTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
        }

        return $itemTransfers;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string>
     */
    protected function extractItemSkus(ArrayObject $itemTransfers): array
    {
        $skus = [];

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getProductConfigurationInstance()) {
                continue;
            }

            $skus[] = $itemTransfer->getSkuOrFail();
        }

        return $skus;
    }
}
