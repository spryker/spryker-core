<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\ProductImage\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\StorageProductImageTransfer;
use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Shared\ProductImage\ProductImageConstants;

class StorageImageMapper implements StorageImageMapperInterface
{

    /**
     * @param StorageProductTransfer $storageProductTransfer
     *
     * @return StorageProductTransfer $storageProductTransfer
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
     * @return ArrayObject
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
