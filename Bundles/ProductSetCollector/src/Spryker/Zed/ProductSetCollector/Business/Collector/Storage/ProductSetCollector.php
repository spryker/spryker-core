<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetCollector\Business\Collector\Storage;

use Generated\Shared\Transfer\ProductSetStorageTransfer;
use Generated\Shared\Transfer\StorageProductImageTransfer;
use Spryker\Shared\ProductSet\ProductSetConfig;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;
use Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainer;
use Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface;
use Spryker\Zed\ProductSetCollector\Persistence\Storage\Propel\ProductSetCollectorQuery;

class ProductSetCollector extends AbstractStoragePropelCollector
{

    /**
     * @var ProductSetQueryContainerInterface
     */
    protected $productSetQueryContainer;

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET;
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $productSetTransfer = new ProductSetStorageTransfer();
        $productSetTransfer = $this->setIdProductAbstract($collectItemData, $productSetTransfer);

        unset($collectItemData[ProductSetCollectorQuery::FIELD_ID_PRODUCT_ABSTRACTS]);

        $productSetTransfer->fromArray($collectItemData, true);
        $productSetTransfer = $this->setProductSetImageSets($productSetTransfer);

        return $productSetTransfer->modifiedToArray();
    }

    /**
     * @return bool
     */
    protected function isStorageTableJoinWithLocaleEnabled()
    {
        return true;
    }

    /**
     * @param array $collectItemData
     * @param \Generated\Shared\Transfer\ProductSetStorageTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer
     */
    protected function setIdProductAbstract(array $collectItemData, ProductSetStorageTransfer $productSetTransfer)
    {
        $idProductAbstracts = explode(',', $collectItemData[ProductSetCollectorQuery::FIELD_ID_PRODUCT_ABSTRACTS]);
        $idProductAbstracts = array_map('intval', $idProductAbstracts);

        $productSetTransfer->setIdProductAbstracts($idProductAbstracts);

        return $productSetTransfer;
    }

    /**
     * @param ProductSetStorageTransfer $productSetTransfer
     *
     * @return ProductSetStorageTransfer
     */
    protected function setProductSetImageSets(ProductSetStorageTransfer $productSetTransfer)
    {
        $this->productSetQueryContainer = new ProductSetQueryContainer(); // FIXME: probably should use ProductSetCollectorQueryContainer

        $imageSetEntities = $this->productSetQueryContainer
            ->queryProductImageSet($productSetTransfer->getIdProductSet())
            ->find();

        // TODO: use new ProductImageFacade methods to get only relevant image sets

        $imageSets = [];
        foreach ($imageSetEntities as $imageSetEntity) {
            $result[$imageSetEntity->getName()] = [];
            foreach ($imageSetEntity->getSpyProductImageSetToProductImages() as $productsToImageEntity) {
                $imageEntity = $productsToImageEntity->getSpyProductImage();
                $storageProductImageTransfer = new StorageProductImageTransfer();
                $storageProductImageTransfer->fromArray($imageEntity->toArray(), true);

                $imageSets[$imageSetEntity->getName()][] = $storageProductImageTransfer->modifiedToArray();
            }
        }

        $productSetTransfer->setImageSets($imageSets);

        return $productSetTransfer;
    }

}
