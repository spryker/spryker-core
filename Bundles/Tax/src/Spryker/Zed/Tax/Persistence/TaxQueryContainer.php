<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxRateTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTaxTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Tax\Persistence\TaxPersistenceFactory getFactory()
 */
class TaxQueryContainer extends AbstractQueryContainer implements TaxQueryContainerInterface
{

    const COL_SUM_TAX_RATE = 'SumTaxRate';
    const COL_ID_ABSTRACT_PRODUCT = 'IdProductAbstract';

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxRateQuery
     */
    public function queryTaxRate($id)
    {
        return $this->getFactory()->createTaxRateQuery()->filterByIdTaxRate($id);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxRateQuery
     */
    public function queryAllTaxRates()
    {
        return $this->getFactory()->createTaxRateQuery();
    }

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function queryTaxSet($id)
    {
        return $this->getFactory()->createTaxSetQuery()->filterByIdTaxSet($id);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function queryAllTaxSets()
    {
        return $this->getFactory()->createTaxSetQuery();
    }

    /**
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     *
     * @return $this
     */
    public function joinTaxRates(ModelCriteria $expandableQuery)
    {
        $expandableQuery
            ->addJoin(
                SpyTaxSetTableMap::COL_ID_TAX_SET,
                SpyTaxSetTaxTableMap::COL_FK_TAX_SET,
                Criteria::LEFT_JOIN // @TODO Change to Criteria::INNER_JOIN as soon as there is a Tax GUI/Importer in Zed
            )
            ->addJoin(
                SpyTaxSetTaxTableMap::COL_FK_TAX_RATE,
                SpyTaxRateTableMap::COL_ID_TAX_RATE,
                Criteria::LEFT_JOIN // @TODO Change to Criteria::INNER_JOIN as soon as there is a Tax GUI/Importer in Zed
            );

        $expandableQuery
            ->withColumn(
                'GROUP_CONCAT(DISTINCT ' . SpyTaxRateTableMap::COL_NAME . ')',
                'tax_rate_names'
            )
            ->withColumn(
                'GROUP_CONCAT(DISTINCT ' . SpyTaxRateTableMap::COL_RATE . ')',
                'tax_rate_rates'
            );

        return $this;
    }

    /**
     * @api
     *
     * @param int[] $allIdProductAbstracts
     * @param string $iso2Code
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function queryTaxSetByIdProductAbstractAndCountry(array $allIdProductAbstracts, $iso2Code)
    {
        return $this->getFactory()->createTaxSetQuery()
            ->useSpyProductAbstractQuery()
                ->filterByIdProductAbstract($allIdProductAbstracts)
                ->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, self::COL_ID_ABSTRACT_PRODUCT)
                ->groupBy(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT)
            ->endUse()
                ->useSpyTaxSetTaxQuery()
                    ->useSpyTaxRateQuery()
                        ->useCountryQuery()
                        ->filterByIso2Code($iso2Code)
                    ->endUse()
                ->endUse()
            ->withColumn('SUM(' . SpyTaxRateTableMap::COL_RATE . ')', self::COL_SUM_TAX_RATE)
            ->endUse()
            ->select([self::COL_SUM_TAX_RATE]);
    }

}
