<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageStorage\Expander;

use Generated\Shared\Transfer\ProductImageSetStorageTransfer;
use Generated\Shared\Transfer\ProductImageStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductImageStorage\Storage\ProductAbstractImageStorageReaderInterface;
use Spryker\Client\ProductImageStorage\Storage\ProductConcreteImageStorageReaderInterface;
use Spryker\Shared\ProductImageStorage\ProductImageStorageConfig;

class ProductViewImageExpander implements ProductViewImageExpanderInterface
{
    /**
     * @var ProductAbstractImageStorageReaderInterface
     */
    protected $productAbstractImageSetReader;

    /**
     * @var ProductConcreteImageStorageReaderInterface
     */
    protected $productConcreteImageSetReader;

    /**
     * @param ProductAbstractImageStorageReaderInterface $productAbstractImageSetReader
     * @param ProductConcreteImageStorageReaderInterface $productConcreteImageSetReader
     */
    public function __construct(ProductAbstractImageStorageReaderInterface $productAbstractImageSetReader, ProductConcreteImageStorageReaderInterface $productConcreteImageSetReader)
    {
        $this->productAbstractImageSetReader = $productAbstractImageSetReader;
        $this->productConcreteImageSetReader = $productConcreteImageSetReader;
    }

    /**
     * @param ProductViewTransfer $productViewTransfer
     * @param string $locale
     * @param string $imageSetName
     *
     * @return ProductViewTransfer
     */
    public function expandProductViewImageData(ProductViewTransfer $productViewTransfer, $locale, $imageSetName = ProductImageStorageConfig::DEFAULT_IMAGE_SET_NAME)
    {
        $images = $this->getImages($productViewTransfer, $locale, $imageSetName);

        if ($images) {
            $productViewTransfer->setImages($images);
        }

        return $productViewTransfer;
    }

    /**
     * @param ProductViewTransfer $productViewTransfer
     * @param string $locale
     * @param string $imageSetName
     *
     * @return ProductImageStorageTransfer[]|null
     */
    protected function getImages(ProductViewTransfer $productViewTransfer, $locale, $imageSetName)
    {
        if ($productViewTransfer->getIdProductConcrete()) {
            $productConcreteImageSetCollection = $this->productConcreteImageSetReader
                ->findProductImageConcreteStorageTransfer($productViewTransfer->getIdProductConcrete(), $locale);

            return $this->getImageSetImages($productConcreteImageSetCollection->getImageSets(), $imageSetName);
        }

        $productAbstractImageSetCollection = $this->productAbstractImageSetReader
            ->findProductImageAbstractStorageTransfer($productViewTransfer->getIdProductAbstract(), $locale);

        return $this->getImageSetImages($productAbstractImageSetCollection->getImageSets(), $imageSetName);
    }

    /**
     * @param ProductImageSetStorageTransfer[] $imageSetStorageCollection
     * @param string $imageSetName
     *
     * @return ProductImageStorageTransfer[]|null
     */
    protected function getImageSetImages($imageSetStorageCollection, $imageSetName)
    {
        foreach ($imageSetStorageCollection as $productImageSetStorageTransfer) {
            if ($productImageSetStorageTransfer->getName() !== $imageSetName) {
                continue;
            }

            return $productImageSetStorageTransfer->getImages();
        }

        if ($imageSetName !== ProductImageStorageConfig::DEFAULT_IMAGE_SET_NAME) {
            return $this->getImageSetImages($imageSetStorageCollection, ProductImageStorageConfig::DEFAULT_IMAGE_SET_NAME);
        }

        return null;
    }
}
