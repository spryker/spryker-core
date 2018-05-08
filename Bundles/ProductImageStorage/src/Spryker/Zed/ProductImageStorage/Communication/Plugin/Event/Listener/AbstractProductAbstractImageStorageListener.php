<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractImageStorageTransfer;
use Generated\Shared\Transfer\ProductImageSetStorageTransfer;
use Generated\Shared\Transfer\ProductImageStorageTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductImageStorage\Communication\ProductImageStorageCommunicationFactory getFactory()
 */
class AbstractProductAbstractImageStorageListener extends AbstractPlugin
{
    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    protected function publish(array $productAbstractIds)
    {
        $spyProductAbstractLocalizedEntities = $this->findProductAbstractLocalizedEntities($productAbstractIds);
        $imageSets = [];
        foreach ($spyProductAbstractLocalizedEntities as $spyProductAbstractLocalizedEntity) {
            $generateProductAbstractImageSets = $this->generateProductAbstractImageSets($spyProductAbstractLocalizedEntity->getFkProductAbstract(), $spyProductAbstractLocalizedEntity->getFkLocale());
            $imageSets[$spyProductAbstractLocalizedEntity->getFkProductAbstract()] = $generateProductAbstractImageSets;
        }

        $spyProductAbstractImageStorageEntities = $this->findProductAbstractImageStorageEntitiesByProductAbstractIds($productAbstractIds);
        $this->storeData($spyProductAbstractLocalizedEntities, $spyProductAbstractImageStorageEntities, $imageSets);
    }

    /**
     * @param array $spyProductAbstractLocalizedEntities
     * @param array $spyProductAbstractImageStorageEntities
     * @param array $imagesSets
     *
     * @return void
     */
    protected function storeData(array $spyProductAbstractLocalizedEntities, array $spyProductAbstractImageStorageEntities, array $imagesSets)
    {
        foreach ($spyProductAbstractLocalizedEntities as $spyProductAbstractLocalizedEntity) {
            $idProduct = $spyProductAbstractLocalizedEntity->getFkProductAbstract();
            $localeName = $spyProductAbstractLocalizedEntity->getLocale()->getLocaleName();
            if (isset($spyProductAbstractImageStorageEntities[$idProduct][$localeName])) {
                $this->storeDataSet($spyProductAbstractLocalizedEntity, $imagesSets, $spyProductAbstractImageStorageEntities[$idProduct][$localeName]);
            } else {
                $this->storeDataSet($spyProductAbstractLocalizedEntity, $imagesSets);
            }
        }
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity
     * @param array $imageSets
     * @param \Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorage|null $spyProductAbstractImageStorage
     *
     * @return void
     */
    protected function storeDataSet(SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity, array $imageSets, ?SpyProductAbstractImageStorage $spyProductAbstractImageStorage = null)
    {
        if ($spyProductAbstractImageStorage === null) {
            $spyProductAbstractImageStorage = new SpyProductAbstractImageStorage();
        }

        if (empty($imageSets[$spyProductAbstractLocalizedEntity->getFkProductAbstract()])) {
            if (!$spyProductAbstractImageStorage->isNew()) {
                $spyProductAbstractImageStorage->delete();
            }

            return;
        }

        $productAbstractStorageTransfer = new ProductAbstractImageStorageTransfer();
        $productAbstractStorageTransfer->setIdProductAbstract($spyProductAbstractLocalizedEntity->getFkProductAbstract());
        $productAbstractStorageTransfer->setImageSets($imageSets[$spyProductAbstractLocalizedEntity->getFkProductAbstract()]);

        $spyProductAbstractImageStorage->setFkProductAbstract($spyProductAbstractLocalizedEntity->getFkProductAbstract());
        $spyProductAbstractImageStorage->setData($productAbstractStorageTransfer->toArray());
        $spyProductAbstractImageStorage->setStore($this->getStoreName());
        $spyProductAbstractImageStorage->setLocale($spyProductAbstractLocalizedEntity->getLocale()->getLocaleName());
        $spyProductAbstractImageStorage->save();
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return array
     */
    protected function generateProductAbstractImageSets($idProductAbstract, $idLocale)
    {
        $imageSetTransfers = $this->getFactory()->getProductImageFacade()->getCombinedAbstractImageSets(
            $idProductAbstract,
            $idLocale
        );

        $imageSets = new ArrayObject();
        foreach ($imageSetTransfers as $imageSetTransfer) {
            $imageSet = (new ProductImageSetStorageTransfer())
                ->setName($imageSetTransfer->getName());
            foreach ($imageSetTransfer->getProductImages() as $productImageTransfer) {
                $imageSet->addImage((new ProductImageStorageTransfer())
                    ->setIdProductImage($productImageTransfer->getIdProductImage())
                    ->setExternalUrlLarge($productImageTransfer->getExternalUrlLarge())
                    ->setExternalUrlSmall($productImageTransfer->getExternalUrlSmall()));
            }
            $imageSets[] = $imageSet;
        }

        return $imageSets;
    }

    /**
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes[]
     */
    protected function findProductAbstractLocalizedEntities(array $productAbstractIds)
    {
        return $this->getQueryContainer()->queryProductAbstractLocalizedByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractImageStorageEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractStorageEntities = $this->getQueryContainer()->queryProductAbstractImageStorageByIds($productAbstractIds)->find();
        $productAbstractStorageEntitiesByIdAndLocale = [];
        foreach ($productAbstractStorageEntities as $productAbstractStorageEntity) {
            $productAbstractStorageEntitiesByIdAndLocale[$productAbstractStorageEntity->getFkProductAbstract()][$productAbstractStorageEntity->getLocale()] = $productAbstractStorageEntity;
        }

        return $productAbstractStorageEntitiesByIdAndLocale;
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()->getStore()->getStoreName();
    }
}
