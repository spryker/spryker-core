<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductAbstractSetTableMap;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductSetDataTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiPersistenceFactory getFactory()
 */
class ProductSetGuiQueryContainer extends AbstractQueryContainer implements ProductSetGuiQueryContainerInterface
{
    public const COL_ALIAS_NAME = 'name';
    public const COL_ALIAS_POSITION = 'position';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryProductSet(LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->getProductSetQueryContainer()
            ->queryProductSet()
            ->useSpyProductSetDataQuery()
                ->filterByFkLocale($localeTransfer->getIdLocale())
            ->endUse()
            ->withColumn(SpyProductSetDataTableMap::COL_NAME, static::COL_ALIAS_NAME);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstract(LocaleTransfer $localeTransfer)
    {
        $query = $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract()
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($localeTransfer->getIdLocale())
            ->endUse()
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, static::COL_ALIAS_NAME);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idProductSet
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractForAssignment($idProductSet, LocaleTransfer $localeTransfer)
    {
        $query = $this->queryProductAbstract($localeTransfer);
        $query->addJoin(
            [SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, $idProductSet],
            [SpyProductAbstractSetTableMap::COL_FK_PRODUCT_ABSTRACT, SpyProductAbstractSetTableMap::COL_FK_PRODUCT_SET],
            Criteria::LEFT_JOIN
        )
            ->addAnd(
                SpyProductAbstractSetTableMap::COL_FK_PRODUCT_SET,
                null,
                Criteria::ISNULL
            );

        return $query;
    }

    /**
     * @api
     *
     * @param int $idProductSet
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractByIdProductSet($idProductSet, LocaleTransfer $localeTransfer)
    {
        $query = $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract()
            ->useSpyProductAbstractSetQuery()
                ->filterByFkProductSet($idProductSet)
            ->endUse()
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($localeTransfer->getIdLocale())
            ->endUse()
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, static::COL_ALIAS_NAME)
            ->withColumn(SpyProductAbstractSetTableMap::COL_POSITION, static::COL_ALIAS_POSITION);

        return $query;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery
     */
    public function queryProductSetWeights()
    {
        $query = $this->getFactory()
            ->getProductSetQueryContainer()
            ->queryProductSet();

        return $query;
    }

    /**
     * @api
     *
     * @param string $productSetKey
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery
     */
    public function queryProductSetByKey($productSetKey)
    {
        $query = $this->getFactory()
            ->getProductSetQueryContainer()
            ->queryProductSet()
            ->filterByProductSetKey($productSetKey);

        return $query;
    }
}
