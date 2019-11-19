<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleStorage\Reader;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToProductImageStorageClientInterface;
use Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToProductStorageClientInterface;

class ProductConcreteStorageReader implements ProductConcreteStorageReaderInterface
{
    protected const MAPPING_TYPE_SKU = ProductConcreteTransfer::SKU;

    /**
     * @var \Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToProductImageStorageClientInterface
     */
    protected $productImageStorageClient;

    /**
     * @param \Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToProductImageStorageClientInterface $productImageStorageClient
     */
    public function __construct(
        ConfigurableBundleStorageToProductStorageClientInterface $productStorageClient,
        ConfigurableBundleStorageToProductImageStorageClientInterface $productImageStorageClient
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->productImageStorageClient = $productImageStorageClient;
    }

    /**
     * @param string[] $skus
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getProductConcretesBySkusForCurrentLocale(array $skus, string $localeName): array
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

        $productViewTransfer = (new ProductViewTransfer())->fromArray($productStorageConcreteData, true);

        return $this->expandProductViewTransferWithImages($productViewTransfer, $localeName);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function expandProductViewTransferWithImages(ProductViewTransfer $productViewTransfer, string $localeName): ProductViewTransfer
    {
        $productConcreteImageStorageTransfer = $this->productImageStorageClient->findProductImageConcreteStorageTransfer(
            $productViewTransfer->getIdProductConcrete(),
            $localeName
        );

        if (!$productConcreteImageStorageTransfer) {
            return $productViewTransfer;
        }

        foreach ($productConcreteImageStorageTransfer->getImageSets() as $productImageSetStorageTransfer) {
            $productViewTransfer = $this->addImagesFromProductImageSetStorageTransferToProductViewTransfer(
                $productViewTransfer,
                $productImageSetStorageTransfer
            );
        }

        return $productViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\ProductImageSetStorageTransfer $productImageSetStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function addImagesFromProductImageSetStorageTransferToProductViewTransfer(
        ProductViewTransfer $productViewTransfer,
        ProductImageSetStorageTransfer $productImageSetStorageTransfer
    ): ProductViewTransfer {
        foreach ($productImageSetStorageTransfer->getImages() as $productImageStorageTransfer) {
            $productViewTransfer->addImage($productImageStorageTransfer);
        }

        return $productViewTransfer;
    }
}
