<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Generated\Shared\Transfer\PriceStorageTransfer;
use Orm\Zed\PriceStorage\Persistence\SpyPriceConcreteStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PriceStorage\Persistence\PriceStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\PriceStorage\Communication\PriceStorageCommunicationFactory getFactory()
 */
class AbstractPriceProductConcreteStorageListener extends AbstractPlugin
{

    const FK_PRODUCT = 'fk_product';

    /**
     * @param array $productConcreteIds
     *
     * @return void
     */
    protected function publish(array $productConcreteIds)
    {
        $spyPriceEntities = $this->findPriceProductEntities($productConcreteIds);
        $priceProductConcreteEntities = [];
        foreach ($spyPriceEntities as $spyPriceEntity) {
            $priceProductConcreteEntities[$spyPriceEntity[static::FK_PRODUCT]][] = $spyPriceEntity;
        }

        $spyPriceStorageEntities = $this->findPriceConcreteStorageEntitiesByProductConcreteIds($productConcreteIds);
        $this->storeData($priceProductConcreteEntities, $spyPriceStorageEntities);
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
     * @param array $priceProductConcreteEntities
     * @param array $spyPriceStorageEntities
     *
     * @return void
     */
    protected function storeData(array $priceProductConcreteEntities, array $spyPriceStorageEntities)
    {
        foreach ($priceProductConcreteEntities as $productConcreteId => $priceEntities) {
            if (isset($spyPriceStorageEntities[$productConcreteId])) {
                $this->storeDataSet($priceEntities, $spyPriceStorageEntities[$productConcreteId]);
            } else {
                $this->storeDataSet($priceEntities);
            }
        }
    }

    /**
     * @param array $priceEntities
     * @param \Orm\Zed\PriceStorage\Persistence\SpyPriceConcreteStorage|null $spyPriceStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(array $priceEntities, SpyPriceConcreteStorage $spyPriceStorageEntity = null)
    {
        if ($spyPriceStorageEntity === null) {
            $spyPriceStorageEntity = new SpyPriceConcreteStorage();
        }

        $priceProductStorageTransfer = new PriceProductStorageTransfer();
        $idProductConcrete = null;
        foreach ($priceEntities as $priceEntity) {
            $price = $priceEntity['price'];
            $priceType = $priceEntity['PriceType']['name'];
            $idProductConcrete = $priceEntity[static::FK_PRODUCT];

            $priceProductStorageTransfer->addPrice(
                (new PriceStorageTransfer())
                    ->setPrice($price)
                    ->setType($priceType)
            );
            if ($priceType === 'DEFAULT') {
                $priceProductStorageTransfer->setDefaultPrice($price);
            }
        }

        $spyPriceStorageEntity->setFkProduct($idProductConcrete);
        $spyPriceStorageEntity->setData($priceProductStorageTransfer->toArray());
        $spyPriceStorageEntity->setStore($this->getStoreName());
        $spyPriceStorageEntity->save();
    }

    /**
     * @param array $productConcreteIds
     *
     * @return array
     */
    protected function findPriceProductEntities(array $productConcreteIds)
    {
        return $this->getQueryContainer()->queryPriceProductConcreteByIds($productConcreteIds)->find()->getData();
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
