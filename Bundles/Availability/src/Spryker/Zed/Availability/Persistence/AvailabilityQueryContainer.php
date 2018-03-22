<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence;

use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityAbstractTableMap;
use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsProductReservationTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
use Orm\Zed\Stock\Persistence\Map\SpyStockTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityPersistenceFactory getFactory()
 */
class AvailabilityQueryContainer extends AbstractQueryContainer implements AvailabilityQueryContainerInterface
{
    const SUM_QUANTITY = 'sumQuantity';
    const ABSTRACT_SKU = 'abstractSku';
    const AVAILABILITY_QUANTITY = 'availabilityQuantity';
    const STOCK_QUANTITY = 'stockQuantity';
    const RESERVATION_QUANTITY = 'reservationQuantity';
    const PRODUCT_NAME = 'productName';
    const CONCRETE_SKU = 'concreteSku';
    const CONCRETE_AVAILABILITY = 'concreteAvailability';
    const CONCRETE_NAME = 'concreteName';
    const ID_PRODUCT = 'idProduct';
    const GROUP_CONCAT = "GROUP_CONCAT";
    const CONCAT = "CONCAT";
    const CONCRETE_NEVER_OUT_OF_STOCK_SET = 'concreteNeverOutOfStockSet';

    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Availability\Persistence\Base\SpyAvailabilityQuery
     */
    public function querySpyAvailabilityBySku($sku)
    {
        return $this->getFactory()
            ->createSpyAvailabilityQuery()
            ->filterBySku($sku);
    }

    /**
     * @api
     *
     * @param string $sku
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\Base\SpyAvailabilityQuery
     */
    public function queryAvailabilityBySkuAndIdStore($sku, $idStore)
    {
        return $this->getFactory()
            ->createSpyAvailabilityQuery()
            ->filterByFkStore($idStore)
            ->filterBySku($sku);
    }

    /**
     * @api
     *
     * @param string $abstractSku
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function querySpyAvailabilityAbstractByAbstractSku($abstractSku)
    {
        return $this->getFactory()
            ->createSpyAvailabilityAbstractQuery()
            ->filterByAbstractSku($abstractSku);
    }

    /**
     * @api
     *
     * @param int $idAvailabilityAbstract
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAvailabilityAbstractByIdAvailabilityAbstract($idAvailabilityAbstract, $idStore)
    {
        return $this->getFactory()
            ->createSpyAvailabilityAbstractQuery()
            ->filterByIdAvailabilityAbstract($idAvailabilityAbstract)
            ->filterByFkStore($idStore);
    }

    /**
     * @api
     *
     * @param int $idAvailabilityAbstract
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\Base\SpyAvailabilityQuery
     */
    public function querySumQuantityOfAvailabilityAbstract($idAvailabilityAbstract, $idStore)
    {
        return $this->getFactory()
            ->createSpyAvailabilityQuery()
            ->filterByFkAvailabilityAbstract($idAvailabilityAbstract)
            ->filterByFkStore($idStore)
            ->withColumn('SUM(' . SpyAvailabilityTableMap::COL_QUANTITY . ')', static::SUM_QUANTITY)
            ->select([static::SUM_QUANTITY]);
    }

    /**
     * @api
     *
     * @param int $idLocale
     * @param int $idStore
     * @param array $stockNames
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityAbstractWithStockByIdLocale($idLocale, $idStore, array $stockNames)
    {
        return $this->querySpyProductAbstractAvailabilityWithStockByIdLocale($idLocale, $stockNames)
            ->withColumn(static::GROUP_CONCAT . '(' . SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK . ')', static::CONCRETE_NEVER_OUT_OF_STOCK_SET)
            ->withColumn('SUM(' . SpyStockProductTableMap::COL_QUANTITY . ')', self::STOCK_QUANTITY)
            ->withColumn(
                static::GROUP_CONCAT . "(" . static::CONCAT . "(" . SpyProductTableMap::COL_SKU . ",':'," . SpyOmsProductReservationTableMap::COL_RESERVATION_QUANTITY . "))",
                static::RESERVATION_QUANTITY
            )
            ->addAnd(SpyAvailabilityAbstractTableMap::COL_FK_STORE, $idStore)
            ->groupBy(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);
    }

    /**
     * @api
     *
     * @param int $idLocale
     * @param array $stockNames
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityWithStockByIdLocale($idLocale, array $stockNames = [])
    {
        return $this->querySpyProductAbstractAvailabilityWithStockByIdLocale($idLocale, $stockNames)
            ->addJoin(SpyProductTableMap::COL_ID_PRODUCT, SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT, Criteria::INNER_JOIN)
            ->addJoin(
                [
                    SpyProductTableMap::COL_SKU,
                    SpyAvailabilityAbstractTableMap::COL_FK_STORE,
                ],
                [
                    SpyAvailabilityTableMap::COL_SKU,
                    SpyAvailabilityTableMap::COL_FK_STORE,
                ],
                Criteria::INNER_JOIN
            )
            ->addAnd(SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE, $idLocale);
    }

    /**
     * @api
     *
     * @param int $idLocale
     * @param array $stockNames
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function querySpyProductAbstractAvailabilityWithStockByIdLocale($idLocale, array $stockNames = [])
    {
        return $this->querySpyProductAbstractAvailabilityWithStock($stockNames)
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, static::PRODUCT_NAME)
            ->withColumn(SpyAvailabilityAbstractTableMap::COL_QUANTITY, static::AVAILABILITY_QUANTITY);
    }

    /**
     * @api
     *
     * @param array $stockNames
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function querySpyProductAbstractAvailabilityWithStock(array $stockNames = [])
    {
        $query = $this->querySpyProductAbstractAvailability();

        if (count($stockNames) > 0) {
            $joinStockProduct = (new Join())->setRightTableName(SpyStockTableMap::TABLE_NAME);
            $joinStockProduct->setJoinType(Criteria::LEFT_JOIN);

            $stockTypeCriterion = (new Criteria())->getNewCriterion(
                SpyStockTableMap::COL_NAME,
                $stockNames,
                Criteria::IN
            );

            $joinStockProduct->setJoinCondition($stockTypeCriterion);

            $query->addJoinObject($joinStockProduct);
        }

        $query->addJoin(
            [
                SpyProductTableMap::COL_ID_PRODUCT,
                SpyStockTableMap::COL_ID_STOCK,
            ],
            [
                SpyStockProductTableMap::COL_FK_PRODUCT,
                SpyStockProductTableMap::COL_FK_STOCK,
            ],
            Criteria::LEFT_JOIN
        )

        ->addJoin(
            [
                SpyProductTableMap::COL_SKU,
                SpyAvailabilityAbstractTableMap::COL_FK_STORE,
            ],
            [
                SpyOmsProductReservationTableMap::COL_SKU,
                SpyOmsProductReservationTableMap::COL_FK_STORE,
            ],
            Criteria::LEFT_JOIN
        );

        return $query;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function querySpyProductAbstractAvailability()
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract()
            ->addJoin(SpyProductAbstractTableMap::COL_SKU, SpyAvailabilityAbstractTableMap::COL_ABSTRACT_SKU, Criteria::INNER_JOIN)
            ->addJoin(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, Criteria::LEFT_JOIN);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     * @param int $idStore
     * @param array $stockNames
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityAbstractWithStockByIdProductAbstractAndIdLocale(
        $idProductAbstract,
        $idLocale,
        $idStore,
        array $stockNames = []
    ) {
        $query = $this->queryAvailabilityAbstractWithStockByIdLocale($idLocale, $idStore, $stockNames)
            ->filterByIdProductAbstract($idProductAbstract);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     * @param int $idStore
     * @param array $stockNames
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityWithStockByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale, $idStore, array $stockNames = [])
    {
        return $this->queryAvailabilityWithStockByIdLocale($idLocale, $stockNames)
            ->withColumn('GROUP_CONCAT(' . SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK . ')', static::CONCRETE_NEVER_OUT_OF_STOCK_SET)
            ->withColumn(SpyProductTableMap::COL_ID_PRODUCT, static::ID_PRODUCT)
            ->withColumn(SpyProductTableMap::COL_SKU, static::CONCRETE_SKU)
            ->withColumn(SpyAvailabilityTableMap::COL_QUANTITY, static::CONCRETE_AVAILABILITY)
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, static::CONCRETE_NAME)
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE)
            ->withColumn('SUM(' . SpyStockProductTableMap::COL_QUANTITY . ')', static::STOCK_QUANTITY)
            ->withColumn(SpyOmsProductReservationTableMap::COL_RESERVATION_QUANTITY, static::RESERVATION_QUANTITY)
            ->addAnd(SpyAvailabilityAbstractTableMap::COL_FK_STORE, $idStore)
            ->filterByIdProductAbstract($idProductAbstract)
            ->select([static::CONCRETE_SKU])
            ->groupBy(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAllAvailabilityAbstracts()
    {
        return $this->getFactory()->createSpyAvailabilityAbstractQuery();
    }
}
