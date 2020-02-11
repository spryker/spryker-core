<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetStorage\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductImageSetStorageTransfer;
use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use Spryker\Client\ProductSetStorage\ProductSetStorageConfig;
use Spryker\Shared\ProductSet\ProductSetConfig;

class ProductSetStorageMapper implements ProductSetStorageMapperInterface
{
    /**
     * @param array $productSetStorageStorageData
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer
     */
    public function mapDataToTransfer(array $productSetStorageStorageData)
    {
        if (ProductSetStorageConfig::isCollectorCompatibilityMode()) {
            $productSetStorageStorageData = $this->formatProductSetCollectorData($productSetStorageStorageData);
        }

        $productSetStorageStorageTransfer = $this->mapProductSetStorageTransfer($productSetStorageStorageData);
        $productSetStorageStorageTransfer = $this->mapProductImages($productSetStorageStorageTransfer);

        return $productSetStorageStorageTransfer;
    }

    /**
     * @param array $productSetStorageData
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer
     */
    protected function mapProductSetStorageTransfer(array $productSetStorageData)
    {
        $productSetStorageStorageTransfer = new ProductSetDataStorageTransfer();
        $productSetStorageStorageTransfer->fromArray($productSetStorageData, true);

        return $productSetStorageStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetDataStorageTransfer $productSetDataStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer
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

    /**
     * @param array $productSetStorageData
     *
     * @return array
     */
    private function formatProductSetCollectorData(array $productSetStorageData): array
    {
        $productSetStorageData['product_abstract_ids'] = $productSetStorageData['id_product_abstracts'];
        unset($productSetStorageData['id_product_abstracts'], $productSetStorageData['images']);

        $imageSets = [];
        foreach ($productSetStorageData['image_sets'] as $imageSetName => $images) {
            $imageSets[] = [
                'name' => $imageSetName,
                'images' => $images,
            ];
        }

        $productSetStorageData['image_sets'] = $imageSets;

        return $productSetStorageData;
    }
}
