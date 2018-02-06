<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Business\Storage;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorage;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainer;
use Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface;

class PriceProductConcreteStorageWriter implements PriceProductConcreteStorageWriterInterface
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
     * @param array $productConcreteIds
     *
     * @return void
     */
    public function publish(array $productConcreteIds)
    {
        $skus = $this->getProductConcreteSkus($productConcreteIds);
        $priceProducts = [];

        foreach ($skus as $idProductConcrete => $sku) {
            $priceProducts[$idProductConcrete] = $this->priceProductFacade->findPricesBySkuGroupedForCurrentStore($sku);
        }

        $spyPriceProductStorageEntities = $this->findPriceConcreteStorageEntitiesByProductConcreteIds($productConcreteIds);
        $this->storeData($priceProducts, $spyPriceProductStorageEntities);
    }

    /**
     * @param array $productConcreteIds
     *
     * @return void
     */
    public function unpublish(array $productConcreteIds)
    {
        $spyPriceConcreteStorageEntities = $this->findPriceConcreteStorageEntitiesByProductConcreteIds($productConcreteIds);
        foreach ($spyPriceConcreteStorageEntities as $spyPriceConcreteStorageEntity) {
            $spyPriceConcreteStorageEntity->delete();
        }
    }

    /**
     * @param array $priceProductConcretes
     * @param array $spyPriceProductStorageEntities
     *
     * @return void
     */
    protected function storeData(array $priceProductConcretes, array $spyPriceProductStorageEntities)
    {
        foreach ($priceProductConcretes as $idProductConcrete => $prices) {
            if (isset($spyPriceProductStorageEntities[$idProductConcrete])) {
                $this->storeDataSet($idProductConcrete, $prices, $spyPriceProductStorageEntities[$idProductConcrete]);
            } else {
                $this->storeDataSet($idProductConcrete, $prices);
            }
        }
    }

    /**
     * @param int $idProductConcrete
     * @param array $prices
     * @param \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorage|null $spyPriceProductStorageEntity
     *
     * @return void
     */
    protected function storeDataSet($idProductConcrete, array $prices, SpyPriceProductConcreteStorage $spyPriceProductStorageEntity = null)
    {
        if ($spyPriceProductStorageEntity === null) {
            $spyPriceProductStorageEntity = new SpyPriceProductConcreteStorage();
        }

        if (!$prices) {
            if (!$spyPriceProductStorageEntity->isNew()) {
                $spyPriceProductStorageEntity->delete();
            }

            return;
        }

        $priceProductStorageTransfer = new PriceProductStorageTransfer();
        $priceProductStorageTransfer->setPrices($prices);

        $spyPriceProductStorageEntity->setFkProduct($idProductConcrete);
        $spyPriceProductStorageEntity->setData($priceProductStorageTransfer->toArray());
        $spyPriceProductStorageEntity->setStore($this->getStoreName());
        $spyPriceProductStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
        $spyPriceProductStorageEntity->save();
    }

    /**
     * @param array $productConcreteIds
     *
     * @return array
     */
    protected function getProductConcreteSkus(array $productConcreteIds)
    {
        return $this->queryContainer
            ->queryProductConcreteSkuByIds($productConcreteIds)
            ->find()
            ->toKeyValue(PriceProductStorageQueryContainer::ID_PRODUCT_CONCRETE, PriceProductStorageQueryContainer::SKU);
    }

    /**
     * @param array $productConcreteIds
     *
     * @return array
     */
    protected function findPriceConcreteStorageEntitiesByProductConcreteIds(array $productConcreteIds)
    {
        return $this->queryContainer->queryPriceConcreteStorageByProductIds($productConcreteIds)->find()->toKeyIndex('fkProduct');
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->store->getStoreName();
    }
}
