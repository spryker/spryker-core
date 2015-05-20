<?php

namespace SprykerFeature\Zed\Tax\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxRateQuery;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSetQuery;

class TaxQueryContainer extends AbstractQueryContainer
{

    /**
     * @param $id
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
     * @param $id
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
}