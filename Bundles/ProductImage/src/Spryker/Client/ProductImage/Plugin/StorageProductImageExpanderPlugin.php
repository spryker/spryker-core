<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImage\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\StorageProductImageTransfer;
use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Product\Dependency\Plugin\StorageProductExpanderPluginInterface;
use Spryker\Shared\ProductImage\ProductImageConfig;

class StorageProductImageExpanderPlugin extends AbstractPlugin implements StorageProductExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     * @param array $productData
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function expandStorageProduct(StorageProductTransfer $storageProductTransfer, array $productData, $locale)
    {
        if (count($storageProductTransfer->getImageSets()) === 0) {
            return $storageProductTransfer;
        }

        $imageSets = $storageProductTransfer->getImageSets();
        if (array_key_exists(ProductImageConfig::DEFAULT_IMAGE_SET_NAME, $imageSets) !== false) {
            $storageProductTransfer->setImages(
                $this->mapStorageProductImageCollection($imageSets[ProductImageConfig::DEFAULT_IMAGE_SET_NAME])
            );
        }

        $storageProductTransfer->setImages(
            $this->mapStorageProductImageCollection(array_shift($imageSets))
        );

        return $storageProductTransfer;
    }

    /**
     * @param array $images
     *
     * @return \ArrayObject
     */
    protected function mapStorageProductImageCollection(array $images)
    {
        $mappedImageCollection = new ArrayObject();
        foreach ($images as $image) {
            $mappedImageCollection->append($this->mapStorageProductImageTransfer($image));
        }

        return $mappedImageCollection;
    }

    /**
     * @param array $imageData
     *
     * @return \Generated\Shared\Transfer\StorageProductImageTransfer
     */
    protected function mapStorageProductImageTransfer(array $imageData)
    {
        $storageImageTransfer = new StorageProductImageTransfer();
        $storageImageTransfer->fromArray($imageData, true);

        return $storageImageTransfer;
    }
}
