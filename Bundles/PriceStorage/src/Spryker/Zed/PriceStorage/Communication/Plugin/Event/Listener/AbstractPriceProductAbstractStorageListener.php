<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Generated\Shared\Transfer\PriceStorageTransfer;
use Orm\Zed\PriceStorage\Persistence\SpyPriceAbstractStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PriceStorage\Persistence\PriceStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\PriceStorage\Communication\PriceStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceStorage\PriceStorageConfig getConfig()
 */
class AbstractPriceProductAbstractStorageListener extends AbstractPlugin
{

    const FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    protected function publish(array $productAbstractIds)
    {
        $spyPriceEntities = $this->findPriceProductEntities($productAbstractIds);
        $priceProductAbstractEntities = [];
        foreach ($spyPriceEntities as $spyPriceEntity) {
            $priceProductAbstractEntities[$spyPriceEntity[static::FK_PRODUCT_ABSTRACT]][] = $spyPriceEntity;
        }

        $spyPriceStorageEntities = $this->findPriceAbstractStorageEntitiesByProductAbstractIds($productAbstractIds);
        $this->storeData($priceProductAbstractEntities, $spyPriceStorageEntities);
    }

    /**
     * @param $productAbstractIds
     *
     * @return void
     */
    protected function refresh(array $productAbstractIds)
    {
        $spyPriceEntities = $this->findPriceProductEntities($productAbstractIds);
        if (!empty($spyPriceEntities)) {
            $this->publish($productAbstractIds);

            return;
        }

        $this->unpublish($productAbstractIds);
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
     * @param array $priceProductAbstractEntities
     * @param array $spyPriceStorageEntities
     *
     * @return void
     */
    protected function storeData(array $priceProductAbstractEntities, array $spyPriceStorageEntities)
    {
        foreach ($priceProductAbstractEntities as $productAbstractId => $priceEntities) {
            if (isset($spyPriceStorageEntities[$productAbstractId])) {
                $this->storeDataSet($priceEntities, $spyPriceStorageEntities[$productAbstractId]);
            } else {
                $this->storeDataSet($priceEntities);
            }
        }
    }

    /**
     * @param array $priceEntities
     * @param \Orm\Zed\PriceStorage\Persistence\SpyPriceAbstractStorage|null $spyPriceStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(array $priceEntities, SpyPriceAbstractStorage $spyPriceStorageEntity = null)
    {
        if ($spyPriceStorageEntity === null) {
            $spyPriceStorageEntity = new SpyPriceAbstractStorage();
        }

        $priceProductStorageTransfer = new PriceProductStorageTransfer();
        $idProductAbstract = null;
        foreach ($priceEntities as $priceEntity) {
            $price = $priceEntity['price'];
            $priceType = $priceEntity['PriceType']['name'];
            $idProductAbstract = $priceEntity[static::FK_PRODUCT_ABSTRACT];

            $priceProductStorageTransfer->addPrice(
                (new PriceStorageTransfer())
                    ->setPrice($price)
                    ->setType($priceType)
            );
            if ($priceType === $this->getConfig()->getDefaultPriceType()) {
                $priceProductStorageTransfer->setDefaultPrice($price);
            }
        }

        $spyPriceStorageEntity->setFkProductAbstract($idProductAbstract);
        $spyPriceStorageEntity->setData($priceProductStorageTransfer->toArray());
        $spyPriceStorageEntity->setStore($this->getStoreName());
        $spyPriceStorageEntity->save();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findPriceProductEntities(array $productAbstractIds)
    {
        return $this->getQueryContainer()->queryPriceProductAbstractByIds($productAbstractIds)->find()->getData();
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
