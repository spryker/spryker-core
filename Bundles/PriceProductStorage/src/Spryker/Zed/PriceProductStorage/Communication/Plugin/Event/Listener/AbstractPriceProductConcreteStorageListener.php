<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainer;

/**
 * @method \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\PriceProductStorage\Communication\PriceProductStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductStorage\PriceProductStorageConfig getConfig()
 */
class AbstractPriceProductConcreteStorageListener extends AbstractPlugin
{
    /**
     * @param array $productConcreteIds
     *
     * @return void
     */
    protected function publish(array $productConcreteIds)
    {
        $skus = $this->getProductConcreteSkus($productConcreteIds);
        $priceProducts = [];

        foreach ($skus as $idProductConcrete => $sku) {
            $priceProducts[$idProductConcrete] = $this->getFactory()->getPriceProductFacade()->findPricesBySkuGroupedForCurrentStore($sku);
        }

        $spyPriceProductStorageEntities = $this->findPriceConcreteStorageEntitiesByProductConcreteIds($productConcreteIds);
        $this->storeData($priceProducts, $spyPriceProductStorageEntities);
    }

    /**
     * @param array $productConcreteIds
     *
     * @return void
     */
    protected function unpublish(array $productConcreteIds)
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
        $spyPriceProductStorageEntity->save();
    }

    /**
     * @param array $productConcreteIds
     *
     * @return array
     */
    protected function getProductConcreteSkus(array $productConcreteIds)
    {
        return $this->getQueryContainer()
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
        return $this->getQueryContainer()->queryPriceConcreteStorageByProductIds($productConcreteIds)->find()->toKeyIndex('fkProduct');
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()->getStore()->getStoreName();
    }
}
