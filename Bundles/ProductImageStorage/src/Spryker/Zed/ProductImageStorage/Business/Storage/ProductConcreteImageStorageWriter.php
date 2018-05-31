<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\ProductConcreteImageStorageTransfer;
use Generated\Shared\Transfer\ProductImageSetStorageTransfer;
use Generated\Shared\Transfer\ProductImageStorageTransfer;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorage;
use Spryker\Zed\ProductImageStorage\Dependency\Facade\ProductImageStorageToProductImageInterface;
use Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainerInterface;

/**
 * @method \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainerInterface queryContainer()
 * @method \Spryker\Zed\ProductImageStorage\Communication\ProductImageStorageCommunicationFactory getFactory()
 */
class ProductConcreteImageStorageWriter implements ProductConcreteImageStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductImageStorage\Dependency\Facade\ProductImageStorageToProductImageInterface
     */
    protected $productImageFacade;

    /**
     * @var \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\ProductImageStorage\Dependency\Facade\ProductImageStorageToProductImageInterface $productImageFacade
     * @param \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     */
    public function __construct(ProductImageStorageToProductImageInterface $productImageFacade, ProductImageStorageQueryContainerInterface $queryContainer, $isSendingToQueue)
    {
        $this->productImageFacade = $productImageFacade;
        $this->queryContainer = $queryContainer;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $productIds
     *
     * @return void
     */
    public function publish(array $productIds)
    {
        $spyProductConcreteLocalizedEntities = $this->findProductConcreteLocalizedEntities($productIds);
        $imageSets = [];
        foreach ($spyProductConcreteLocalizedEntities as $spyProductConcreteLocalizedEntity) {
            $generateProductConcreteImageSets = $this->generateProductConcreteImageSets(
                $spyProductConcreteLocalizedEntity->getFkProduct(),
                $spyProductConcreteLocalizedEntity->getSpyProduct()->getFkProductAbstract(),
                $spyProductConcreteLocalizedEntity->getFkLocale()
            );
            $imageSets[$spyProductConcreteLocalizedEntity->getFkProduct()] = $generateProductConcreteImageSets;
        }

        $spyProductConcreteImageStorageEntities = $this->findProductConcreteImageStorageEntitiesByProductConcreteIds($productIds);
        $this->storeData($spyProductConcreteLocalizedEntities, $spyProductConcreteImageStorageEntities, $imageSets);
    }

    /**
     * @param array $productIds
     *
     * @return void
     */
    public function unpublish(array $productIds)
    {
        $spyProductConcreteImageStorageEntities = $this->findProductConcreteImageStorageEntitiesByProductConcreteIds($productIds);
        foreach ($spyProductConcreteImageStorageEntities as $spyProductConcreteImageStorageLocalizedEntities) {
            foreach ($spyProductConcreteImageStorageLocalizedEntities as $spyProductConcreteImageStorageLocalizedEntity) {
                $spyProductConcreteImageStorageLocalizedEntity->delete();
            }
        }
    }

    /**
     * @param array $spyProductConcreteLocalizedEntities
     * @param array $spyProductConcreteImageStorageEntities
     * @param array $imagesSets
     *
     * @return void
     */
    protected function storeData(array $spyProductConcreteLocalizedEntities, array $spyProductConcreteImageStorageEntities, array $imagesSets)
    {
        foreach ($spyProductConcreteLocalizedEntities as $spyProductConcreteLocalizedEntity) {
            $idProduct = $spyProductConcreteLocalizedEntity->getFkProduct();
            $localeName = $spyProductConcreteLocalizedEntity->getLocale()->getLocaleName();
            if (isset($spyProductConcreteImageStorageEntities[$idProduct][$localeName])) {
                $this->storeDataSet($spyProductConcreteLocalizedEntity, $imagesSets, $spyProductConcreteImageStorageEntities[$idProduct][$localeName]);

                continue;
            }

            $this->storeDataSet($spyProductConcreteLocalizedEntity, $imagesSets);
        }
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes $spyProductLocalizedEntity
     * @param array $imageSets
     * @param \Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorage|null $spyProductConcreteImageStorage
     *
     * @return void
     */
    protected function storeDataSet(SpyProductLocalizedAttributes $spyProductLocalizedEntity, array $imageSets, ?SpyProductConcreteImageStorage $spyProductConcreteImageStorage = null)
    {
        if ($spyProductConcreteImageStorage === null) {
            $spyProductConcreteImageStorage = new SpyProductConcreteImageStorage();
        }

        if (empty($imageSets[$spyProductLocalizedEntity->getFkProduct()])) {
            if (!$spyProductConcreteImageStorage->isNew()) {
                $spyProductConcreteImageStorage->delete();
            }

            return;
        }

        $productConcreteStorageTransfer = new ProductConcreteImageStorageTransfer();
        $productConcreteStorageTransfer->setIdProductConcrete($spyProductLocalizedEntity->getFkProduct());
        $productConcreteStorageTransfer->setImageSets($imageSets[$spyProductLocalizedEntity->getFkProduct()]);

        $spyProductConcreteImageStorage->setFkProduct($spyProductLocalizedEntity->getFkProduct());
        $spyProductConcreteImageStorage->setData($productConcreteStorageTransfer->toArray());
        $spyProductConcreteImageStorage->setLocale($spyProductLocalizedEntity->getLocale()->getLocaleName());
        $spyProductConcreteImageStorage->setIsSendingToQueue($this->isSendingToQueue);
        $spyProductConcreteImageStorage->save();
    }

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return array|\ArrayObject
     */
    protected function generateProductConcreteImageSets($idProductConcrete, $idProductAbstract, $idLocale)
    {
        $imageSetTransfers = $this->productImageFacade->getCombinedConcreteImageSets(
            $idProductConcrete,
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
     * @param array $productConcreteIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes[]
     */
    protected function findProductConcreteLocalizedEntities(array $productConcreteIds)
    {
        return $this->queryContainer->queryProductLocalizedByIds($productConcreteIds)->find()->getData();
    }

    /**
     * @param array $productConcreteIds
     *
     * @return array
     */
    protected function findProductConcreteImageStorageEntitiesByProductConcreteIds(array $productConcreteIds)
    {
        $productConcreteStorageEntities = $this->queryContainer->queryProductConcreteImageStorageByIds($productConcreteIds)->find();
        $productConcreteStorageEntitiesByIdAndLocale = [];
        foreach ($productConcreteStorageEntities as $productConcreteStorageEntity) {
            $productConcreteStorageEntitiesByIdAndLocale[$productConcreteStorageEntity->getFkProduct()][$productConcreteStorageEntity->getLocale()] = $productConcreteStorageEntity;
        }

        return $productConcreteStorageEntitiesByIdAndLocale;
    }
}
