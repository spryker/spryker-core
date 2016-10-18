<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\ProductImage\Builder;

use Generated\Shared\Transfer\StorageProductImageTransfer;
use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Shared\ProductImage\ProductImageConstants;

class StorageImageBuilder implements StorageImageBuilderInterface
{

    /**
     * @param array $persistedProductData
     *
     * @return array
     */
    public function getDisplayImagesForSelectedProduct(array $persistedProductData)
    {
        if (!isset($persistedProductData[StorageProductTransfer::IMAGE_SETS])) {
             return [];
        }

        if (count($persistedProductData[StorageProductTransfer::IMAGE_SETS]) === 0) {
            return [];
        }

        $imageSets = $persistedProductData[StorageProductTransfer::IMAGE_SETS];
        if (isset($imageSets[ProductImageConstants::DEFAULT_IMAGE_SET_NAME])) {
            return $this->mapStorageProductImageCollection($imageSets[ProductImageConstants::DEFAULT_IMAGE_SET_NAME]);
        } else {
            return $this->mapStorageProductImageCollection(array_shift($imageSets));
        }
    }

    /**
     * @param array $images
     *
     * @return array
     */
    protected function mapStorageProductImageCollection(array $images)
    {
        $mappedImageCollection = [];

        foreach ($images as $image) {
            $mappedImageCollection[] = $this->mapStorageProductImageCollection($image);
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
