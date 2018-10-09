<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractImageStorageTransfer;
use Generated\Shared\Transfer\ProductImageSetStorageTransfer;
use Generated\Shared\Transfer\ProductImageStorageTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorage;
use Spryker\Zed\ProductImageStorage\Dependency\Facade\ProductImageStorageToProductImageInterface;
use Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainerInterface;
use Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageRepositoryInterface;

class ProductAbstractImageStorageWriter implements ProductAbstractImageStorageWriterInterface
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
     * @var \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageRepositoryInterface
     */
    protected $repository;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\ProductImageStorage\Dependency\Facade\ProductImageStorageToProductImageInterface $productImageFacade
     * @param \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageRepositoryInterface $repository
     * @param bool $isSendingToQueue
     */
    public function __construct(
        ProductImageStorageToProductImageInterface $productImageFacade,
        ProductImageStorageQueryContainerInterface $queryContainer,
        ProductImageStorageRepositoryInterface $repository,
        $isSendingToQueue
    ) {
        $this->productImageFacade = $productImageFacade;
        $this->queryContainer = $queryContainer;
        $this->repository = $repository;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $spyProductAbstractLocalizedEntities = $this->findProductAbstractLocalizedEntities($productAbstractIds);
        $imageSets = [];
        $productAbstractImageSetsBulk = $this->getImageSetsIndexedByProductAbstractIdAndLocale(
            $this->repository->getProductImageSetsByFkAbstractProductIn($productAbstractIds)
        );

        foreach ($spyProductAbstractLocalizedEntities as $spyProductAbstractLocalizedEntity) {
            $idProductAbstract = $spyProductAbstractLocalizedEntity->getFkProductAbstract();
            if (!isset($productAbstractImageSetsBulk[$idProductAbstract])) {
                continue;
            }

            $imageSets[$idProductAbstract][$spyProductAbstractLocalizedEntity->getIdAbstractAttributes()] = $this->generateProductAbstractImageSets(
                $productAbstractImageSetsBulk[$spyProductAbstractLocalizedEntity->getFkProductAbstract()][$spyProductAbstractLocalizedEntity->getFkLocale()]
            );
        }

        $spyProductAbstractImageStorageEntities = $this->findProductAbstractImageStorageEntitiesByProductAbstractIds($productAbstractIds);
        $this->storeData($spyProductAbstractLocalizedEntities, $spyProductAbstractImageStorageEntities, $imageSets);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $spyProductAbstractImageStorageEntities = $this->findProductAbstractImageStorageEntitiesByProductAbstractIds($productAbstractIds);
        foreach ($spyProductAbstractImageStorageEntities as $spyProductAbstractImageStorageLocalizedEntities) {
            /** @var \Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorage $spyProductAbstractImageStorageLocalizedEntity */
            foreach ($spyProductAbstractImageStorageLocalizedEntities as $spyProductAbstractImageStorageLocalizedEntity) {
                $spyProductAbstractImageStorageLocalizedEntity->delete();
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[] $productImageSets
     *
     * @return array
     */
    protected function getImageSetsIndexedByProductAbstractIdAndLocale(array $productImageSets): array
    {
        $indexedProductImageSets = [];

        foreach ($productImageSets as $productImageSet) {
            if ($productImageSet->getFkProductAbstract()) {
                $indexedProductImageSets[$productImageSet->getFkProductAbstract()][$productImageSet->getFkLocale()][] = $productImageSet;
            }
        }

        return $indexedProductImageSets;
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

                continue;
            }

            $this->storeDataSet($spyProductAbstractLocalizedEntity, $imagesSets);
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
        $productAbstractStorageTransfer->setImageSets($imageSets[$spyProductAbstractLocalizedEntity->getFkProductAbstract()][$spyProductAbstractLocalizedEntity->getIdAbstractAttributes()]);

        $spyProductAbstractImageStorage->setFkProductAbstract($spyProductAbstractLocalizedEntity->getFkProductAbstract());
        $spyProductAbstractImageStorage->setData($productAbstractStorageTransfer->toArray());
        $spyProductAbstractImageStorage->setLocale($spyProductAbstractLocalizedEntity->getLocale()->getLocaleName());
        $spyProductAbstractImageStorage->setIsSendingToQueue($this->isSendingToQueue);
        $spyProductAbstractImageStorage->save();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[] $productImageSetEntityTransfers
     *
     * @return \ArrayObject
     */
    protected function generateProductAbstractImageSets(array $productImageSetEntityTransfers)
    {
        $imageSets = new ArrayObject();

        foreach ($productImageSetEntityTransfers as $productImageSetEntityTransfer) {
            $imageSet = (new ProductImageSetStorageTransfer())
                ->setName($productImageSetEntityTransfer->getName());
            foreach ($productImageSetEntityTransfer->getSpyProductImageSetToProductImages() as $productImageSetToProductImageTransfer) {
                $productImageTransfer = $productImageSetToProductImageTransfer->getSpyProductImage();

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
        return $this->queryContainer->queryProductAbstractLocalizedByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorage[][]
     */
    protected function findProductAbstractImageStorageEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractStorageEntities = $this->queryContainer->queryProductAbstractImageStorageByIds($productAbstractIds)->find();
        $productAbstractStorageEntitiesByIdAndLocale = [];
        foreach ($productAbstractStorageEntities as $productAbstractStorageEntity) {
            $productAbstractStorageEntitiesByIdAndLocale[$productAbstractStorageEntity->getFkProductAbstract()][$productAbstractStorageEntity->getLocale()] = $productAbstractStorageEntity;
        }

        return $productAbstractStorageEntitiesByIdAndLocale;
    }
}
