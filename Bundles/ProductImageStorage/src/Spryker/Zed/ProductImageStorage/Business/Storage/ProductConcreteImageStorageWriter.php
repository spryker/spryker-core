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
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorage;
use Spryker\Zed\ProductImageStorage\Dependency\Facade\ProductImageStorageToProductImageInterface;
use Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainerInterface;
use Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageRepositoryInterface;

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
     * @param array $productIds
     *
     * @return void
     */
    public function publish(array $productIds)
    {
        $spyProductConcreteLocalizedEntities = $this->findProductConcreteLocalizedEntities($productIds);
        $imageSets = $this->generateProductConcreteImageSets($productIds);

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
        $productConcreteStorageTransfer->setImageSets($imageSets[$spyProductLocalizedEntity->getFkProduct()][$spyProductLocalizedEntity->getIdProductAttributes()]);

        $spyProductConcreteImageStorage->setFkProduct($spyProductLocalizedEntity->getFkProduct());
        $spyProductConcreteImageStorage->setData($productConcreteStorageTransfer->toArray());
        $spyProductConcreteImageStorage->setLocale($spyProductLocalizedEntity->getLocale()->getLocaleName());
        $spyProductConcreteImageStorage->setIsSendingToQueue($this->isSendingToQueue);
        $spyProductConcreteImageStorage->save();
    }

    /**
     * @param array $productIds
     *
     * @return array
     */
    protected function generateProductConcreteImageSets(array $productIds)
    {
        $imageSetEntitiesByIdProduct = $this->getCombinedImageSets($productIds);

        $imageSets = [];
        foreach ($imageSetEntitiesByIdProduct as $idProduct => $imageLocalizedAttributes) {
            if (!isset($imageSets[$idProduct])) {
                $imageSets[$idProduct] = [];
            }

            foreach ($imageLocalizedAttributes as $idProductAttribute => $imageLocalizedSets) {
                $imageSets[$idProduct][$idProductAttribute] = $this->generateProductImageSetStorageTransfers($imageLocalizedSets);
            }
        }

        return $imageSets;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[] $productImageSetEntityTransfers
     *
     * @return \ArrayObject
     */
    protected function generateProductImageSetStorageTransfers(ArrayObject $productImageSetEntityTransfers): ArrayObject
    {
        $productImageSetStorageTransfers = new ArrayObject();

        foreach ($productImageSetEntityTransfers as $imageLocalizedSet) {
            $imageSet = (new ProductImageSetStorageTransfer())
                ->setName($imageLocalizedSet->getName());
            foreach ($imageLocalizedSet->getSpyProductImageSetToProductImages() as $imageSetToProductImage) {
                $productImage = $imageSetToProductImage->getSpyProductImage();
                $imageSet->addImage((new ProductImageStorageTransfer())
                    ->setIdProductImage($productImage->getIdProductImage())
                    ->setExternalUrlLarge($productImage->getExternalUrlLarge())
                    ->setExternalUrlSmall($productImage->getExternalUrlSmall()));
            }
            $productImageSetStorageTransfers[] = $imageSet;
        }

        return $productImageSetStorageTransfers;
    }

    /**
     * @param int[] $productIds
     *
     * @return array
     */
    protected function getCombinedImageSets(array $productIds): array
    {
        $productLocalizedAttributes = $this->repository->getProductLocalizedAttributesWithProductByIdProductIn($productIds);

        $productFks = array_column($productLocalizedAttributes, SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT);
        $productAbstractFks = array_column($productLocalizedAttributes, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT);

        $productImageSets = $this->repository->getProductImageSetsByFkProductInOrFkAbstractProductIn($productFks, $productAbstractFks);

        [$productImageSetsIndexedByFkProductAbstract, $productImageSetsIndexedByFkProduct]
            = $this->indexImageSetsByProductAbstractAndProduct($productImageSets);

        $combinedImageSets = [];
        foreach ($productLocalizedAttributes as $productLocalizedAttribute) {
            $idProduct = $productLocalizedAttribute[SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT];
            $colIdProductAttributes = $productLocalizedAttribute[SpyProductLocalizedAttributesTableMap::COL_ID_PRODUCT_ATTRIBUTES];

            $combinedImageSets[$idProduct][$colIdProductAttributes] = $this->getCombinedImageSetForLocalizedAttribute(
                $productLocalizedAttribute,
                $productImageSetsIndexedByFkProductAbstract,
                $productImageSetsIndexedByFkProduct
            );
        }

        return $combinedImageSets;
    }

    /**
     * @param array $productLocalizedAttribute
     * @param array $productImageSetsIndexedByFkProductAbstract pass by reference to get rid of array copying
     * @param array $productImageSetsIndexedByFkProduct
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[]
     */
    protected function getCombinedImageSetForLocalizedAttribute(
        array $productLocalizedAttribute,
        array &$productImageSetsIndexedByFkProductAbstract,
        array &$productImageSetsIndexedByFkProduct
    ): ArrayObject {
        $combinedImageSet = new ArrayObject();

        $colFkProduct = $productLocalizedAttribute[SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT];
        $colFkProductAbstract = $productLocalizedAttribute[SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT];
        $colFkLocale = $productLocalizedAttribute[SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE];

        foreach ($productImageSetsIndexedByFkProduct[$colFkProduct][$colFkLocale] ?? [] as $item) {
            $combinedImageSet->append($item);
        }

        foreach ($productImageSetsIndexedByFkProductAbstract[$colFkProductAbstract][$colFkLocale] ?? [] as $item) {
            $combinedImageSet->append($item);
        }

        return $combinedImageSet;
    }

    /**
     * Returns list of sets indexed by fk product abstract and product.
     * Just one method and list because of performance.
     *
     * @param \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[] $productImageSets
     *
     * @return array
     */
    protected function indexImageSetsByProductAbstractAndProduct(array $productImageSets): array
    {
        $productImageSetsIndexedByFkProductAbstract = [];
        $productImageSetsIndexedByFkProduct = [];

        foreach ($productImageSets as $productImageSet) {
            if ($productImageSet->getFkProductAbstract()) {
                $productImageSetsIndexedByFkProductAbstract[$productImageSet->getFkProductAbstract()][$productImageSet->getFkLocale()][] = $productImageSet;
            }

            if ($productImageSet->getFkProduct()) {
                $productImageSetsIndexedByFkProduct[$productImageSet->getFkProduct()][$productImageSet->getFkLocale()][] = $productImageSet;
            }
        }

        return [$productImageSetsIndexedByFkProductAbstract, $productImageSetsIndexedByFkProduct];
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
     * @return \Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage[][]
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
