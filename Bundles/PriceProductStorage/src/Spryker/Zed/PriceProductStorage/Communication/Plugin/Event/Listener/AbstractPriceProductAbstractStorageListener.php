<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainer;

/**
 * @method \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\PriceProductStorage\Communication\PriceProductStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductStorage\PriceProductStorageConfig getConfig()
 */
class AbstractPriceProductAbstractStorageListener extends AbstractPlugin
{
    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    protected function publish(array $productAbstractIds)
    {
        $skus = $this->getProductAbstractSkus($productAbstractIds);
        $priceProducts = [];

        foreach ($skus as $idProductAbstract => $sku) {
            $priceProducts[$idProductAbstract] = $this->getFactory()->getPriceProductFacade()->findPricesBySkuGroupedForCurrentStore($sku);
        }

        $spyPriceProductStorageEntities = $this->findPriceAbstractStorageEntitiesByProductAbstractIds($productAbstractIds);
        $this->storeData($priceProducts, $spyPriceProductStorageEntities);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    protected function unpublish(array $productAbstractIds)
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
            } else {
                $this->storeDataSet($idProductAbstract, $prices);
            }
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
        $spyPriceProductStorageEntity->save();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function getProductAbstractSkus(array $productAbstractIds)
    {
        return $this->getQueryContainer()
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
        return $this->getQueryContainer()->queryPriceAbstractStorageByPriceAbstractIds($productAbstractIds)->find()->toKeyIndex('fkProductAbstract');
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()->getStore()->getStoreName();
    }
}
