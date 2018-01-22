<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetStorage\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductImageSetStorageTransfer;
use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use Spryker\Shared\ProductSet\ProductSetConfig;

class ProductSetStorageMapper implements ProductSetStorageMapperInterface
{
    /**
     * @param array $ProductSetStorageStorageData
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer
     */
    public function mapDataToTransfer(array $ProductSetStorageStorageData)
    {
        $ProductSetStorageStorageTransfer = $this->mapProductSetStorageTransfer($ProductSetStorageStorageData);
        $ProductSetStorageStorageTransfer = $this->mapProductImages($ProductSetStorageStorageTransfer);

        return $ProductSetStorageStorageTransfer;
    }

    /**
     * @param array $ProductSetStorageData
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer
     */
    protected function mapProductSetStorageTransfer(array $ProductSetStorageData)
    {
        $ProductSetStorageStorageTransfer = new ProductSetDataStorageTransfer();
        $ProductSetStorageStorageTransfer->fromArray($ProductSetStorageData, true);

        return $ProductSetStorageStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetDataStorageTransfer $productSetDataStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer $productSetDataStorageTransfer
     */
    public function mapProductImages(ProductSetDataStorageTransfer $productSetDataStorageTransfer)
    {
        if (count($productSetDataStorageTransfer->getImageSets()) === 0) {
            return $productSetDataStorageTransfer;
        }

        $imageSetTransfers = $productSetDataStorageTransfer->getImageSets();
        $defaultImageSets = [];
        $otherImageSets = [];

        foreach ($imageSetTransfers as $imageSetTransfer) {
            if ($imageSetTransfer->getName() === ProductSetConfig::DEFAULT_IMAGE_SET_NAME) {
                $defaultImageSets[] = $imageSetTransfer;

                continue;
            }

            $otherImageSets[] = $imageSetTransfer;
        }

        usort($otherImageSets, function (ProductImageSetStorageTransfer $a, ProductImageSetStorageTransfer $b) {
            return strcmp($a->getName(), $b->getName());
        });

        $imageSetTransfers = array_merge($defaultImageSets, $otherImageSets);
        $productSetDataStorageTransfer->setImageSets(new ArrayObject($imageSetTransfers));

        return $productSetDataStorageTransfer;
    }
}
