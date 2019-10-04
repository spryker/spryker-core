<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSet\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductSetStorageTransfer;
use Generated\Shared\Transfer\StorageProductImageTransfer;
use Spryker\Shared\ProductSet\ProductSetConfig;

class ProductSetStorageMapper implements ProductSetStorageMapperInterface
{
    /**
     * @param array $productSetStorageData
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer
     */
    public function mapDataToTransfer(array $productSetStorageData)
    {
        $productSetStorageTransfer = $this->mapProductSetTransfer($productSetStorageData);
        $productSetStorageTransfer = $this->mapProductImages($productSetStorageTransfer);

        return $productSetStorageTransfer;
    }

    /**
     * @param array $productSetData
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer
     */
    protected function mapProductSetTransfer(array $productSetData)
    {
        $productSetStorageTransfer = new ProductSetStorageTransfer();
        $productSetStorageTransfer->fromArray($productSetData, true);

        return $productSetStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetStorageTransfer $storageProductTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer
     */
    public function mapProductImages(ProductSetStorageTransfer $storageProductTransfer)
    {
        if (count($storageProductTransfer->getImageSets()) === 0) {
            return $storageProductTransfer;
        }

        $imageSets = $storageProductTransfer->getImageSets();
        if (array_key_exists(ProductSetConfig::DEFAULT_IMAGE_SET_NAME, $imageSets) !== false) {
            $storageProductTransfer->setImages(
                $this->mapStorageProductImageCollection($imageSets[ProductSetConfig::DEFAULT_IMAGE_SET_NAME])
            );
        }

        ksort($imageSets);

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
