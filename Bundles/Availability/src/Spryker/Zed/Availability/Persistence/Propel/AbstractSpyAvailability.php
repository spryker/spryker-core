<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence\Propel;

use Orm\Zed\Availability\Persistence\Base\SpyAvailability as BaseSpyAvailability;
use Orm\Zed\Availability\Persistence\Base\SpyAvailabilityAbstractQuery;
use Orm\Zed\Availability\Persistence\Base\SpyAvailabilityQuery;
use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityTableMap;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract;
use Orm\Zed\Product\Persistence\Base\SpyProduct;
use Orm\Zed\Product\Persistence\Base\SpyProductQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'spy_availability' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
abstract class AbstractSpyAvailability extends BaseSpyAvailability
{

    const SUM_QUANTITY = 'sum_quantity';
    const ABSTRACT_SKU = 'abstractSku';

    /**
     * @param ConnectionInterface|null $con
     *
     * @return bool
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if ($this->fk_availability_abstract === null) {
            $availabilityAbstractEntity = $this->findOrCreateSpyAvailabilityAbstract();
            $this->setFkAvailabilityAbstract($availabilityAbstractEntity->getIdAvailabilityAbstract());
        }

        return true;
    }

    /**
     * @param ConnectionInterface|null $con
     *
     * @return void
     */
    public function postSave(ConnectionInterface $con = null)
    {
        $availabilityAbstractEntity = $this->getAvailabilityAbstract();
        $sumQuantity = $this->sumQuantityOfAvailabilityAbstract();

        $availabilityAbstractEntity->setQuantity($sumQuantity);
        $availabilityAbstractEntity->save();
    }

    /**
     * @return SpyAvailabilityAbstract
     */
    protected function findOrCreateSpyAvailabilityAbstract()
    {
        $productEntity = $this->findSpyProductBySku();
        $availabilityAbstractEntity = $this->findSpyAvailabilityAbstractByIdProductAbstract($productEntity->getFkProductAbstract());

        if ($availabilityAbstractEntity !== null) {
            return $availabilityAbstractEntity;
        }

        return $this->createSpyAvailabilityAbstract($productEntity);
    }

    /**
     * @param SpyProduct $product
     *
     * @return SpyAvailabilityAbstract
     */
    protected function createSpyAvailabilityAbstract(SpyProduct $product)
    {
        $availableAbstractEntity = new SpyAvailabilityAbstract();
        $availableAbstractEntity->setSku($product->getAbstractSku());
        $availableAbstractEntity->setFkProductAbstract($product->getFkProductAbstract());
        $availableAbstractEntity->save();

        return $availableAbstractEntity;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return SpyAvailabilityAbstract
     */
    protected function findSpyAvailabilityAbstractByIdProductAbstract($idProductAbstract)
    {
        return $this->createSpyAvailabilityAbstractQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->findOne();
    }

    /**
     * @return SpyAvailabilityAbstract
     */
    protected function getAvailabilityAbstract()
    {
        return $this->createSpyAvailabilityAbstractQuery()
            ->filterByIdAvailabilityAbstract($this->fk_availability_abstract)
            ->findOne();
    }

    /**
     * @return int
     */
    protected function sumQuantityOfAvailabilityAbstract()
    {
        return $this->createSpyAvailabilityQuery()
            ->filterByFkAvailabilityAbstract($this->fk_availability_abstract)
            ->withColumn('SUM(' . SpyAvailabilityTableMap::COL_QUANTITY . ')', self::SUM_QUANTITY)
            ->select([self::SUM_QUANTITY])
            ->findOne();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function findSpyProductBySku()
    {
        return $this->createSpyProductQuery()
            ->filterBySku($this->sku)
            ->innerJoinSpyProductAbstract()
            ->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT)
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, self::ABSTRACT_SKU)
            ->findOne();
    }

    /**
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    protected function createSpyAvailabilityAbstractQuery()
    {
        return SpyAvailabilityAbstractQuery::create();
    }

    /**
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityQuery
     */
    protected function createSpyAvailabilityQuery()
    {
        return SpyAvailabilityQuery::create();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function createSpyProductQuery()
    {
        return SpyProductQuery::create();
    }

}
