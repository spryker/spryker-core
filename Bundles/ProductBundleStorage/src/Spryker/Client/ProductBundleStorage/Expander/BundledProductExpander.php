<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundleStorage\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ProductBundleStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToProductStorageClientInterface;
use Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToStorageClientInterface;
use Spryker\Client\ProductBundleStorage\Dependency\Service\ProductBundleStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductBundleStorage\ProductBundleStorageConfig;

class BundledProductExpander implements BundledProductExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductBundleStorage\Dependency\Service\ProductBundleStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductBundleStorage\Dependency\Service\ProductBundleStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToProductStorageClientInterface $productStorageClient
     */
    public function __construct(
        ProductBundleStorageToStorageClientInterface $storageClient,
        ProductBundleStorageToSynchronizationServiceInterface $synchronizationService,
        ProductBundleStorageToProductStorageClientInterface $productStorageClient
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewWithBundledProducts(ProductViewTransfer $productViewTransfer, string $localeName): ProductViewTransfer
    {
        $productBundleStorageTransferData = $this->storageClient->get($this->generateKey($productViewTransfer->getIdProductConcrete()));

        if (!$productBundleStorageTransferData) {
            return $productViewTransfer;
        }

        $productViewTransfer->setBundledProducts(
            new ArrayObject($this->getBundledProducts($productBundleStorageTransferData, $localeName))
        );

        return $productViewTransfer;
    }

    /**
     * @param array $productBundleStorageTransferData
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function getBundledProducts(array $productBundleStorageTransferData, string $localeName): array
    {
        $productBundleStorageTransfer = $this->mapToProductBundleStorageTransfer($productBundleStorageTransferData);
        $mappedProductForBundleStorageTransfers = $this->mapBundledProductsByIdProductConcrete($productBundleStorageTransfer);

        $productViewTransfers = $this->productStorageClient->getProductConcreteViewTransfers(array_keys($mappedProductForBundleStorageTransfers), $localeName);

        foreach ($productViewTransfers as $productViewTransfer) {
            $productForBundleStorageTransfer = $mappedProductForBundleStorageTransfers[$productViewTransfer->getIdProductConcrete()] ?? null;

            if (!$productForBundleStorageTransfer) {
                continue;
            }

            $productViewTransfer->setQuantity($productForBundleStorageTransfer->getQuantity());
        }

        return $productViewTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductBundleStorageTransfer $productBundleStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductForBundleStorageTransfer[]
     */
    protected function mapBundledProductsByIdProductConcrete(ProductBundleStorageTransfer $productBundleStorageTransfer): array
    {
        $mappedProductForBundleStorageTransfers = [];

        foreach ($productBundleStorageTransfer->getBundledProducts() as $productForBundleStorageTransfer) {
            $mappedProductForBundleStorageTransfers[$productForBundleStorageTransfer->getIdProductConcrete()] = $productForBundleStorageTransfer;
        }

        return $mappedProductForBundleStorageTransfers;
    }

    /**
     * @param array $productBundleStorageTransferData
     *
     * @return \Generated\Shared\Transfer\ProductBundleStorageTransfer
     */
    protected function mapToProductBundleStorageTransfer(array $productBundleStorageTransferData): ProductBundleStorageTransfer
    {
        return (new ProductBundleStorageTransfer())->fromArray($productBundleStorageTransferData, true);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return string
     */
    protected function generateKey(int $idProductConcrete): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($idProductConcrete);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductBundleStorageConfig::PRODUCT_BUNDLE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
