<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductImage\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\StorageProductImageTransfer;
use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Shared\ProductImage\ProductImageConstants;

class StorageImageMapper implements StorageImageMapperInterface
{

    /**
     * @param \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     */
    public function mapProductImages(StorageProductTransfer $storageProductTransfer)
    {
        if (count($storageProductTransfer->getImageSets()) === 0) {
            return $storageProductTransfer;
        }

        $imageSets = $storageProductTransfer->getImageSets();
        if (array_key_exists(ProductImageConstants::DEFAULT_IMAGE_SET_NAME, $imageSets) !== false) {
            $storageProductTransfer->setImages(
                $this->mapStorageProductImageCollection($imageSets[ProductImageConstants::DEFAULT_IMAGE_SET_NAME])
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
