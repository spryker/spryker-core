<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Business\Storage;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainer;
use Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface;

class PriceProductAbstractStorageWriter implements PriceProductAbstractStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface
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
     * @param \Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Shared\Kernel\Store $store
     * @param bool $isSendingToQueue
     */
    public function __construct(PriceProductStorageToPriceProductFacadeInterface $priceProductFacade, PriceProductStorageQueryContainerInterface $queryContainer, Store $store, $isSendingToQueue)
    {
        $this->priceProductFacade = $priceProductFacade;
        $this->queryContainer = $queryContainer;
        $this->store = $store;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $skus = $this->getProductAbstractSkus($productAbstractIds);
        $priceProducts = [];

        foreach ($skus as $idProductAbstract => $sku) {
            $priceProducts[$idProductAbstract] = $this->priceProductFacade->findPricesBySkuGroupedForCurrentStore($sku);
        }

        $spyPriceProductStorageEntities = $this->findPriceAbstractStorageEntitiesByProductAbstractIds($productAbstractIds);
        $this->storeData($priceProducts, $spyPriceProductStorageEntities);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $spyPriceAbstractStorageEntities = $this->findPriceAbstractStorageEntitiesByProductAbstractIds($productAbstractIds);
        foreach ($spyPriceAbstractStorageEntities as $spyPriceAbstractStorageEntity) {
            $spyPriceAbstractStorageEntity->delete();
        }
    }

    /**
     * @param array $priceProductAbstracts
     * @param array $spyPriceProductStorageEntities
     *
     * @return void
     */
    protected function storeData(array $priceProductAbstracts, array $spyPriceProductStorageEntities)
    {
        foreach ($priceProductAbstracts as $idProductAbstract => $prices) {
            if (isset($spyPriceProductStorageEntities[$idProductAbstract])) {
                $this->storeDataSet($idProductAbstract, $prices, $spyPriceProductStorageEntities[$idProductAbstract]);

                continue;
            }

            $this->storeDataSet($idProductAbstract, $prices);
        }
    }

    /**
     * @param int $idProductAbstract
     * @param array $prices
     * @param \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage|null $spyPriceProductStorageEntity
     *
     * @return void
     */
    protected function storeDataSet($idProductAbstract, array $prices, SpyPriceProductAbstractStorage $spyPriceProductStorageEntity = null)
    {
        if ($spyPriceProductStorageEntity === null) {
            $spyPriceProductStorageEntity = new SpyPriceProductAbstractStorage();
        }

        if (!$prices) {
            if (!$spyPriceProductStorageEntity->isNew()) {
                $spyPriceProductStorageEntity->delete();
            }

            return;
        }

        $priceProductStorageTransfer = new PriceProductStorageTransfer();
        $priceProductStorageTransfer->setPrices($prices);

        $spyPriceProductStorageEntity->setFkProductAbstract($idProductAbstract);
        $spyPriceProductStorageEntity->setData($priceProductStorageTransfer->toArray());
        $spyPriceProductStorageEntity->setStore($this->getStoreName());
        $spyPriceProductStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
        $spyPriceProductStorageEntity->save();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function getProductAbstractSkus(array $productAbstractIds)
    {
        return $this->queryContainer
            ->queryProductAbstractSkuByIds($productAbstractIds)
            ->find()
            ->toKeyValue(PriceProductStorageQueryContainer::ID_PRODUCT_ABSTRACT, PriceProductStorageQueryContainer::SKU);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findPriceAbstractStorageEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        return $this->queryContainer->queryPriceAbstractStorageByPriceAbstractIds($productAbstractIds)->find()->toKeyIndex('fkProductAbstract');
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->store->getStoreName();
    }
}
