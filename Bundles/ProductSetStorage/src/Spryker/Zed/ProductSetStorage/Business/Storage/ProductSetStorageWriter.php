<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\ProductImageSetStorageTransfer;
use Generated\Shared\Transfer\ProductImageStorageTransfer;
use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use Orm\Zed\ProductSetStorage\Persistence\SpyProductSetStorage;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\ProductSetStorage\Persistence\ProductSetStorageQueryContainerInterface;

class ProductSetStorageWriter implements ProductSetStorageWriterInterface
{
    const COL_ID_PRODUCT_SET = 'id_product_set';

    /**
     * @var \Spryker\Zed\ProductSetStorage\Persistence\ProductSetStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @var array
     */
    protected $superAttributes = [];

    /**
     * @param \Spryker\Zed\ProductSetStorage\Persistence\ProductSetStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Shared\Kernel\Store $store
     * @param bool $isSendingToQueue
     */
    public function __construct(ProductSetStorageQueryContainerInterface $queryContainer, Store $store, $isSendingToQueue)
    {
        $this->queryContainer = $queryContainer;
        $this->store = $store;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $productSetIds
     *
     * @return void
     */
    public function publish(array $productSetIds)
    {
        $spyProductSetLocalizedEntities = $this->findProductSetLocalizedEntities($productSetIds);
        $spyProductSetStorageEntities = $this->findProductSetStorageEntitiesByProductSetIds($productSetIds);

        $this->storeData($spyProductSetLocalizedEntities, $spyProductSetStorageEntities);
    }

    /**
     * @param array $productSetIds
     *
     * @return void
     */
    public function unpublish(array $productSetIds)
    {
        $spyProductSetStorageEntities = $this->findProductSetStorageEntitiesByProductSetIds($productSetIds);
        foreach ($spyProductSetStorageEntities as $spyProductSetStorageEntityLocales) {
            foreach ($spyProductSetStorageEntityLocales as $spyProductSetStorageEntityLocale) {
                $spyProductSetStorageEntityLocale->delete();
            }
        }
    }

    /**
     * @param array $spyProductSetLocalizedEntities
     * @param array $spyProductSetStorageEntities
     *
     * @return void
     */
    protected function storeData(array $spyProductSetLocalizedEntities, array $spyProductSetStorageEntities)
    {
        foreach ($spyProductSetLocalizedEntities as $spyProductSetLocalizedEntity) {
            $idProductSet = $spyProductSetLocalizedEntity['SpyProductSet'][static::COL_ID_PRODUCT_SET];
            $localeName = $spyProductSetLocalizedEntity['SpyLocale']['locale_name'];
            if (isset($spyProductSetStorageEntities[$idProductSet][$localeName])) {
                $this->storeDataSet($spyProductSetLocalizedEntity, $spyProductSetStorageEntities[$idProductSet][$localeName]);
            } else {
                $this->storeDataSet($spyProductSetLocalizedEntity);
            }
        }
    }

    /**
     * @param array $spyProductSetLocalizedEntity
     * @param \Orm\Zed\ProductSetStorage\Persistence\SpyProductSetStorage|null $spyProductSetStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(array $spyProductSetLocalizedEntity, SpyProductSetStorage $spyProductSetStorageEntity = null)
    {
        $productSetStorageTransfer = new ProductSetDataStorageTransfer();
        if ($spyProductSetStorageEntity === null) {
            $spyProductSetStorageEntity = new SpyProductSetStorage();
        }

        $productSetStorageTransfer->fromArray($spyProductSetLocalizedEntity, true);
        $productSetStorageTransfer->fromArray($spyProductSetLocalizedEntity['SpyProductSet'], true);
        $productAbstractIds = [];
        foreach ($spyProductSetLocalizedEntity['SpyProductSet']['SpyProductAbstractSets'] as $productAbstract) {
            $productAbstractIds[] = $productAbstract['fk_product_abstract'];
        }

        $productImageSet = $this->getProductImageSets($spyProductSetLocalizedEntity);

        $productSetStorageTransfer->setProductAbstractIds($productAbstractIds);
        $productSetStorageTransfer->setImageSets($productImageSet);
        $spyProductSetStorageEntity->setFkProductSet($spyProductSetLocalizedEntity['SpyProductSet'][static::COL_ID_PRODUCT_SET]);
        $spyProductSetStorageEntity->setData($productSetStorageTransfer->toArray());
        $spyProductSetStorageEntity->setStore($this->getStoreName());
        $spyProductSetStorageEntity->setLocale($spyProductSetLocalizedEntity['SpyLocale']['locale_name']);
        $spyProductSetStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
        $spyProductSetStorageEntity->save();
    }

    /**
     * @param array $productSetIds
     *
     * @return array
     */
    protected function findProductSetLocalizedEntities(array $productSetIds)
    {
        return $this->queryContainer->queryProductSetDataByIds($productSetIds)->find()->getData();
    }

    /**
     * @param array $productSetIds
     *
     * @return array
     */
    protected function findProductSetStorageEntitiesByProductSetIds(array $productSetIds)
    {
        $productSetStorageEntities = $this->queryContainer->queryProductSetStorageByIds($productSetIds)->find();
        $productSetStorageEntitiesByIdAndLocale = [];
        foreach ($productSetStorageEntities as $productSetStorageEntity) {
            $productSetStorageEntitiesByIdAndLocale[$productSetStorageEntity->getFkProductSet()][$productSetStorageEntity->getLocale()] = $productSetStorageEntity;
        }

        return $productSetStorageEntitiesByIdAndLocale;
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->store->getStoreName();
    }

    /**
     * @param array $spyProductSetLocalizedEntity
     *
     * @return array|\ArrayObject
     */
    protected function getProductImageSets(array $spyProductSetLocalizedEntity)
    {
        $productImageSet = new ArrayObject();
        foreach ($spyProductSetLocalizedEntity['SpyProductSet']['SpyProductImageSets'] as $spyProductImageSets) {
            $productImageSetStorageTransfer = new ProductImageSetStorageTransfer();
            $productImageSetStorageTransfer->setName($spyProductImageSets['name']);
            foreach ($spyProductImageSets['SpyProductImageSetToProductImages'] as $productImageSetToProductImage) {
                $productImageStorageTransfer = new ProductImageStorageTransfer();
                $productImageStorageTransfer->setIdProductImage($productImageSetToProductImage['SpyProductImage']['id_product_image']);
                $productImageStorageTransfer->setExternalUrlSmall($productImageSetToProductImage['SpyProductImage']['external_url_small']);
                $productImageStorageTransfer->setExternalUrlLarge($productImageSetToProductImage['SpyProductImage']['external_url_large']);
                $productImageSetStorageTransfer->addImage($productImageStorageTransfer);
            }
            $productImageSet[] = $productImageSetStorageTransfer;
        }

        return $productImageSet;
    }
}
