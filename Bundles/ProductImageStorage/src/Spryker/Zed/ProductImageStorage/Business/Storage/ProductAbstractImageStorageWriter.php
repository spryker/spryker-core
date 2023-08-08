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
use Spryker\Zed\ProductImageStorage\Dependency\Facade\ProductImageStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductImageStorage\Dependency\Facade\ProductImageStorageToProductImageInterface;
use Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainerInterface;
use Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageRepositoryInterface;

class ProductAbstractImageStorageWriter implements ProductAbstractImageStorageWriterInterface
{
    /**
     * @uses \Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetToProductImageTableMap::COL_FK_PRODUCT_IMAGE_SET
     *
     * @var string
     */
    protected const COL_FK_PRODUCT_IMAGE_SET = 'spy_product_image_set_to_product_image.fk_product_image_set';

    /**
     * @var \Spryker\Zed\ProductImageStorage\Dependency\Facade\ProductImageStorageToProductImageInterface
     */
    protected ProductImageStorageToProductImageInterface $productImageFacade;

    /**
     * @var \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainerInterface
     */
    protected ProductImageStorageQueryContainerInterface $queryContainer;

    /**
     * @var \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageRepositoryInterface
     */
    protected ProductImageStorageRepositoryInterface $repository;

    /**
     * @var \Spryker\Zed\ProductImageStorage\Dependency\Facade\ProductImageStorageToEventBehaviorFacadeInterface
     */
    protected ProductImageStorageToEventBehaviorFacadeInterface $eventBehaviorFacade;

    /**
     * @deprecated Use {@link \Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()} instead.
     *
     * @var bool
     */
    protected bool $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\ProductImageStorage\Dependency\Facade\ProductImageStorageToProductImageInterface $productImageFacade
     * @param \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageRepositoryInterface $repository
     * @param \Spryker\Zed\ProductImageStorage\Dependency\Facade\ProductImageStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param bool $isSendingToQueue
     */
    public function __construct(
        ProductImageStorageToProductImageInterface $productImageFacade,
        ProductImageStorageQueryContainerInterface $queryContainer,
        ProductImageStorageRepositoryInterface $repository,
        ProductImageStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        bool $isSendingToQueue
    ) {
        $this->productImageFacade = $productImageFacade;
        $this->queryContainer = $queryContainer;
        $this->repository = $repository;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $productAbstractLocalizedEntities = $this->findProductAbstractLocalizedEntities($productAbstractIds);
        $imageSets = [];
        $productAbstractImageSetsBulk = $this->getImageSetsIndexedByProductAbstractIdAndLocale(
            $this->repository->getProductImageSetsByFkAbstractProductIn($productAbstractIds),
        );
        $defaultProductAbstractImageSetsBulk = $this->getImageSetsIndexedByProductAbstractId(
            $this->repository->getDefaultAbstractProductImageSetsByIdAbstractProductIn($productAbstractIds),
        );

        foreach ($productAbstractLocalizedEntities as $productAbstractLocalizedEntity) {
            $idProductAbstract = $productAbstractLocalizedEntity->getFkProductAbstract();
            $idLocale = $productAbstractLocalizedEntity->getFkLocale();
            $idAbstractAttributes = $productAbstractLocalizedEntity->getIdAbstractAttributes();

            if (
                !isset($productAbstractImageSetsBulk[$idProductAbstract][$idLocale]) &&
                !isset($defaultProductAbstractImageSetsBulk[$idProductAbstract])
            ) {
                continue;
            }

            if (isset($productAbstractImageSetsBulk[$idProductAbstract][$idLocale])) {
                $imageSets[$idProductAbstract][$idAbstractAttributes] = $this->generateProductAbstractImageSets(
                    $productAbstractImageSetsBulk[$idProductAbstract][$idLocale],
                );

                continue;
            }

            $imageSets[$idProductAbstract][$idAbstractAttributes] = $this->generateProductAbstractImageSets(
                $defaultProductAbstractImageSetsBulk[$idProductAbstract],
            );
        }

        $productAbstractImageStorageEntities = $this->findProductAbstractImageStorageEntitiesByProductAbstractIds($productAbstractIds);
        $this->storeData($productAbstractLocalizedEntities, $productAbstractImageStorageEntities, $imageSets);
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $imageSets = [];

        $productAbstractLocalizedEntities = $this->findProductAbstractLocalizedEntities($productAbstractIds);
        $defaultProductAbstractImageSetsBulk = $this->getImageSetsIndexedByProductAbstractId(
            $this->repository->getDefaultAbstractProductImageSetsByIdAbstractProductIn($productAbstractIds),
        );
        $productAbstractImageSetsBulk = $this->getImageSetsIndexedByProductAbstractIdAndLocale(
            $this->repository->getProductImageSetsByFkAbstractProductIn($productAbstractIds),
        );
        $productAbstractImageStorageEntities = $this->findProductAbstractImageStorageEntitiesByProductAbstractIds($productAbstractIds);

        foreach ($productAbstractLocalizedEntities as $productAbstractLocalizedEntity) {
            $idProductAbstract = $productAbstractLocalizedEntity->getFkProductAbstract();
            $idLocale = $productAbstractLocalizedEntity->getFkLocale();
            $idAbstractAttributes = $productAbstractLocalizedEntity->getIdAbstractAttributes();
            $localeName = $productAbstractLocalizedEntity->getLocale()->getLocaleName();

            if (isset($productAbstractImageSetsBulk[$idProductAbstract][$idLocale])) {
                $imageSets[$idProductAbstract][$idAbstractAttributes] = $this->generateProductAbstractImageSets(
                    $productAbstractImageSetsBulk[$idProductAbstract][$idLocale],
                );

                continue;
            }

            if (isset($defaultProductAbstractImageSetsBulk[$idProductAbstract])) {
                $imageSets[$idProductAbstract][$idAbstractAttributes] = $this->generateProductAbstractImageSets(
                    $defaultProductAbstractImageSetsBulk[$idProductAbstract],
                );

                continue;
            }

            if (!isset($productAbstractImageStorageEntities[$idProductAbstract][$localeName])) {
                continue;
            }

            $productAbstractImageStorageEntities[$idProductAbstract][$localeName]->delete();
            unset($productAbstractImageStorageEntities[$idProductAbstract][$localeName]);
        }

        $this->storeData($productAbstractLocalizedEntities, $productAbstractImageStorageEntities, $imageSets);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function deleteProductAbstractImageStorageCollectionByProductImageSetToProductImageEvents(array $eventEntityTransfers): void
    {
        $productImageSetIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            static::COL_FK_PRODUCT_IMAGE_SET,
        );

        $productAbstractIds = $this->repository->getProductAbstractIdsByProductImageSetIds($productImageSetIds);

        $this->unpublish($productAbstractIds);
    }

    /**
     * @param array<\Generated\Shared\Transfer\SpyProductImageSetEntityTransfer> $productImageSets
     *
     * @return array
     */
    protected function getImageSetsIndexedByProductAbstractId(array $productImageSets): array
    {
        $indexedProductImageSets = [];

        foreach ($productImageSets as $productImageSet) {
            if ($productImageSet->getFkProductAbstract()) {
                $indexedProductImageSets[$productImageSet->getFkProductAbstract()][] = $productImageSet;
            }
        }

        return $indexedProductImageSets;
    }

    /**
     * @param array<\Generated\Shared\Transfer\SpyProductImageSetEntityTransfer> $productImageSets
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

            if (!isset($imagesSets[$idProduct][$spyProductAbstractLocalizedEntity->getIdAbstractAttributes()])) {
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
    protected function storeDataSet(
        SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity,
        array $imageSets,
        ?SpyProductAbstractImageStorage $spyProductAbstractImageStorage = null
    ) {
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
     * @param array<\Generated\Shared\Transfer\SpyProductImageSetEntityTransfer> $productImageSetEntityTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetStorageTransfer>
     */
    protected function generateProductAbstractImageSets(array $productImageSetEntityTransfers)
    {
        /** @var \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetStorageTransfer> $productImageSetStorageTransfers */
        $productImageSetStorageTransfers = new ArrayObject();

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
            $productImageSetStorageTransfers[] = $imageSet;
        }

        return $productImageSetStorageTransfers;
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes>
     */
    protected function findProductAbstractLocalizedEntities(array $productAbstractIds)
    {
        return $this->queryContainer->queryProductAbstractLocalizedByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<array<\Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorage>>
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
