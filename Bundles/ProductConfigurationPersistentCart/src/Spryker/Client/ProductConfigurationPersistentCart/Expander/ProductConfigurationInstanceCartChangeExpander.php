<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationPersistentCart\Expander;

use ArrayObject;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceConditionsTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceCriteriaTransfer;
use Spryker\Client\ProductConfigurationPersistentCart\Dependency\Client\ProductConfigurationPersistentCartToProductConfigurationStorageClientInterface;

class ProductConfigurationInstanceCartChangeExpander implements ProductConfigurationInstanceCartChangeExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationPersistentCart\Dependency\Client\ProductConfigurationPersistentCartToProductConfigurationStorageClientInterface
     */
    protected ProductConfigurationPersistentCartToProductConfigurationStorageClientInterface $productConfigurationStorageClient;

    /**
     * @param \Spryker\Client\ProductConfigurationPersistentCart\Dependency\Client\ProductConfigurationPersistentCartToProductConfigurationStorageClientInterface $productConfigurationStorageClient
     */
    public function __construct(ProductConfigurationPersistentCartToProductConfigurationStorageClientInterface $productConfigurationStorageClient)
    {
        $this->productConfigurationStorageClient = $productConfigurationStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandPersistentCartChangeWithProductConfigurationInstance(
        PersistentCartChangeTransfer $persistentCartChangeTransfer,
        array $params = []
    ): PersistentCartChangeTransfer {
        $productConfigurationInstanceCollectionTransfer = $this->getProductConfigurationInstanceCollection($persistentCartChangeTransfer);

        if (!$productConfigurationInstanceCollectionTransfer->getProductConfigurationInstances()->count()) {
            return $persistentCartChangeTransfer;
        }

        return $this->expandItemsWithProductConfigurationInstance(
            $persistentCartChangeTransfer,
            $productConfigurationInstanceCollectionTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer
     */
    protected function getProductConfigurationInstanceCollection(
        PersistentCartChangeTransfer $persistentCartChangeTransfer
    ): ProductConfigurationInstanceCollectionTransfer {
        $productConfigurationInstanceConditionsTransfer = new ProductConfigurationInstanceConditionsTransfer();

        foreach ($persistentCartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getProductConfigurationInstance()) {
                continue;
            }

            $productConfigurationInstanceConditionsTransfer->addSku($itemTransfer->getSkuOrFail());
        }

        if (!$productConfigurationInstanceConditionsTransfer->getSkus()) {
            return new ProductConfigurationInstanceCollectionTransfer();
        }

        $productConfigurationInstanceCriteriaTransfer = (new ProductConfigurationInstanceCriteriaTransfer())
            ->setProductConfigurationInstanceConditions($productConfigurationInstanceConditionsTransfer);

        return $this->productConfigurationStorageClient
            ->getProductConfigurationInstanceCollection($productConfigurationInstanceCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer $productConfigurationInstanceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    protected function expandItemsWithProductConfigurationInstance(
        PersistentCartChangeTransfer $persistentCartChangeTransfer,
        ProductConfigurationInstanceCollectionTransfer $productConfigurationInstanceCollectionTransfer
    ): PersistentCartChangeTransfer {
        $expandedPersistentCartChangeItemTransfers = new ArrayObject();
        $productConfigurationInstanceTransfersIndexedBySku = $productConfigurationInstanceCollectionTransfer->getProductConfigurationInstances();

        foreach ($persistentCartChangeTransfer->getItems() as $itemTransfer) {
            $productConfigurationInstanceTransfer = $productConfigurationInstanceTransfersIndexedBySku[$itemTransfer->getSkuOrFail()] ?? null;

            if (!$itemTransfer->getProductConfigurationInstance() && $productConfigurationInstanceTransfer) {
                $itemTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
            }

            $expandedPersistentCartChangeItemTransfers->append($itemTransfer);
        }

        return $persistentCartChangeTransfer->setItems($expandedPersistentCartChangeItemTransfers);
    }
}
