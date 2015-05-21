<?php

namespace SprykerFeature\Zed\Tax\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Tax\Persistence\Propel\Map\SpyTaxRateTableMap;
use SprykerFeature\Zed\Tax\Persistence\Propel\Map\SpyTaxSetTableMap;
use SprykerFeature\Zed\Tax\Persistence\Propel\Map\SpyTaxSetTaxTableMap;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxRateQuery;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSetQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class TaxQueryContainer extends AbstractQueryContainer
{
    /**
     * @param int $id
     *
     * @return SpyTaxRateQuery
     */
    public function queryTaxRate($id)
    {
        return SpyTaxRateQuery::create()->filterByIdTaxRate($id);
    }

    /**
     * @return SpyTaxRateQuery
     */
    public function queryAllTaxRates()
    {
        return SpyTaxRateQuery::create();
    }

    /**
     * @param int $id
     *
     * @return SpyTaxSetQuery
     */
    public function queryTaxSet($id)
    {
        return SpyTaxSetQuery::create()->filterByIdTaxSet($id);
    }

    /**
     * @return SpyTaxSetQuery
     */
    public function queryAllTaxSets()
    {
        return SpyTaxSetQuery::create();
    }

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return $this
     */
    public function joinTaxRates(ModelCriteria $expandableQuery)
    {
        $expandableQuery
            ->addJoin(
                SpyTaxSetTableMap::COL_ID_TAX_SET,
                SpyTaxSetTaxTableMap::COL_FK_TAX_SET,
                Criteria::LEFT_JOIN // @TODO Check workflow of Criteria::INNER_JOIN should be used instead
            )
            ->addJoin(
                SpyTaxSetTaxTableMap::COL_FK_TAX_RATE,
                SpyTaxRateTableMap::COL_ID_TAX_RATE,
                Criteria::LEFT_JOIN // @TODO Check workflow of Criteria::INNER_JOIN should be used instead
            )
        ;

        $expandableQuery->withColumn(
            'GROUP_CONCAT(spy_product.sku)',
            'concrete_skus'
        );

        return $this;
    }
}
