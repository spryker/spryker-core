<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationCart\Expander;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToProductConfigurationStorageClientInterface;

class ProductConfigurationInstanceCartChangeExpander implements ProductConfigurationInstanceCartChangeExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToProductConfigurationStorageClientInterface
     */
    protected $productConfigurationStorageClient;

    /**
     * @param \Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToProductConfigurationStorageClientInterface $productConfigurationStorageClient
     */
    public function __construct(ProductConfigurationCartToProductConfigurationStorageClientInterface $productConfigurationStorageClient)
    {
        $this->productConfigurationStorageClient = $productConfigurationStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeWithProductConfigurationInstance(
        CartChangeTransfer $cartChangeTransfer,
        array $params = []
    ): CartChangeTransfer {
        $skus = $this->extractItemSkus($cartChangeTransfer->getItems());

        if (!$skus) {
            return $cartChangeTransfer;
        }

        $productConfigurationInstancesIndexedBySku = $this->productConfigurationStorageClient->findProductConfigurationInstancesIndexedBySku($skus);

        if (!$productConfigurationInstancesIndexedBySku) {
            return $cartChangeTransfer;
        }

        $expandedItemTransfers = $this->expandItemsWithProductConfigurationInstance(
            $productConfigurationInstancesIndexedBySku,
            $cartChangeTransfer->getItems()
        );

        $cartChangeTransfer->setItems($expandedItemTransfers);

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer[] $productConfigurationInstancesIndexedBySku
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
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
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return string[]
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
