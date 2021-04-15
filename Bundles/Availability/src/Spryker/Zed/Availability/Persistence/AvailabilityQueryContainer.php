<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence;

use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityAbstractTableMap;
use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityTableMap;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Orm\Zed\Oms\Persistence\Map\SpyOmsProductReservationTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
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
    public const SUM_QUANTITY = 'sumQuantity';
    public const ABSTRACT_SKU = 'abstractSku';
    public const AVAILABILITY_QUANTITY = 'availabilityQuantity';
    public const STOCK_QUANTITY = 'stockQuantity';
    public const RESERVATION_QUANTITY = 'reservationQuantity';
    public const PRODUCT_NAME = 'productName';
    public const CONCRETE_SKU = 'concreteSku';
    public const CONCRETE_AVAILABILITY = 'concreteAvailability';
    public const CONCRETE_NAME = 'concreteName';
    public const ID_PRODUCT = 'idProduct';
    public const GROUP_CONCAT = 'GROUP_CONCAT';
    public const CONCAT = 'CONCAT';
    public const CONCRETE_NEVER_OUT_OF_STOCK_SET = 'concreteNeverOutOfStockSet';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityQuery
     */
    public function querySpyAvailabilityBySku($sku)
    {
        return $this->getFactory()
            ->createSpyAvailabilityQuery()
            ->filterBySku($sku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityQuery
     */
    public function queryAvailabilityBySkuAndIdStore($sku, $idStore)
    {
        return $this->getFactory()
            ->createSpyAvailabilityQuery()
            ->filterByFkStore($idStore)
            ->filterBySku($sku);
    }

    /**
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idAvailabilityAbstract
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityQuery
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
     * {@inheritDoc}
     *
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
                static::GROUP_CONCAT . '(' . static::CONCAT . '(' . SpyProductTableMap::COL_SKU . ",':'," . SpyOmsProductReservationTableMap::COL_RESERVATION_QUANTITY . '))',
                static::RESERVATION_QUANTITY
            )
            ->addAnd(SpyAvailabilityAbstractTableMap::COL_FK_STORE, $idStore)
            ->groupBy(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idLocale
     * @param int $idStore
     * @param int[] $stockIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityAbstractWithCurrentStockAndReservedProductsAggregated(
        int $idLocale,
        int $idStore,
        array $stockIds
    ): SpyProductAbstractQuery {
        $query = $this
            ->querySpyProductAbstractAvailability()
            ->innerJoinSpyProductAbstractLocalizedAttributes();

        $query = $this->joinOmsProductReservation($query, $idStore);
        $query = $this->joinStockProduct($query, $stockIds);

        $query
            ->addAnd(SpyAvailabilityAbstractTableMap::COL_FK_STORE, $idStore)
            ->addAnd(SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE, $idLocale)
            ->addGroupByColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT)
            ->addGroupByColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_ID_ABSTRACT_ATTRIBUTES)
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, static::PRODUCT_NAME)
            ->withColumn('SUM(' . SpyStockProductTableMap::COL_QUANTITY . ')', static::STOCK_QUANTITY)
            ->withColumn(
                'SUM(' . SpyOmsProductReservationTableMap::COL_RESERVATION_QUANTITY . ')',
                static::RESERVATION_QUANTITY
            );

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idLocale
     * @param array $stockNames
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery|\Propel\Runtime\ActiveQuery\Criteria
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idLocale
     * @param array $stockNames
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery|\Propel\Runtime\ActiveQuery\Criteria
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $stockNames
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery|\Propel\Runtime\ActiveQuery\Criteria
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery|\Propel\Runtime\ActiveQuery\Criteria
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
     * {@inheritDoc}
     *
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
        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $query */
        $query = $this->queryAvailabilityAbstractWithStockByIdLocale($idLocale, $idStore, $stockNames);
        $query->filterByIdProductAbstract($idProductAbstract);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     * @param int $idLocale
     * @param int $idStore
     * @param string[] $stockNames
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractWithStockByProductAbstractIds(
        array $productAbstractIds,
        int $idLocale,
        int $idStore,
        array $stockNames = []
    ): SpyProductAbstractQuery {
        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $query */
        $query = $this->queryAvailabilityAbstractWithStockByIdLocale($idLocale, $idStore, $stockNames);
        $query->filterByIdProductAbstract_In($productAbstractIds);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAllAvailabilityAbstracts()
    {
        return $this->getFactory()->createSpyAvailabilityAbstractQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAvailabilityAbstractByIdStore(int $idStore): SpyAvailabilityAbstractQuery
    {
        /** @var \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery $availabilityAbstractQuery */
        $availabilityAbstractQuery = $this->queryAllAvailabilityAbstracts()
            ->addAnd(SpyAvailabilityAbstractTableMap::COL_FK_STORE, $idStore);

        return $availabilityAbstractQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $query
     * @param int $idStore
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function joinOmsProductReservation(SpyProductAbstractQuery $query, int $idStore): SpyProductAbstractQuery
    {
        $omsProductReservationFkStoreCriterion = (new Criteria())->getNewCriterion(
            SpyOmsProductReservationTableMap::COL_FK_STORE,
            $idStore
        );
        $joinOmsProductReservation = new Join(
            SpyProductTableMap::COL_SKU,
            SpyOmsProductReservationTableMap::COL_SKU,
            Criteria::LEFT_JOIN
        );
        $joinOmsProductReservation->buildJoinCondition($query);
        $joinOmsProductReservation->getJoinCondition()->addAnd($omsProductReservationFkStoreCriterion);

        $query->addJoinObject($joinOmsProductReservation);

        return $query;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $query
     * @param int[] $stockIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function joinStockProduct(SpyProductAbstractQuery $query, array $stockIds): SpyProductAbstractQuery
    {
        $stockIdsCriterion = (new Criteria())->getNewCriterion(
            SpyStockProductTableMap::COL_FK_STOCK,
            $stockIds,
            Criteria::IN
        );
        $joinStockProduct = new Join(
            SpyProductTableMap::COL_ID_PRODUCT,
            SpyStockProductTableMap::COL_FK_PRODUCT,
            Criteria::LEFT_JOIN
        );
        $joinStockProduct->buildJoinCondition($query);
        $joinStockProduct->getJoinCondition()->addAnd($stockIdsCriterion);

        $query->addJoinObject($joinStockProduct);

        return $query;
    }
}
