<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Tax\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxRateQuery;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSetQuery;

interface TaxQueryContainerInterface
{

    /**
     * @param int $id
     *
     * @return SpyTaxRateQuery
     */
    public function queryTaxRate($id);

    /**
     * @return SpyTaxRateQuery
     */
    public function queryAllTaxRates();

    /**
     * @param int $id
     *
     * @return SpyTaxSetQuery
     */
    public function queryTaxSet($id);

    /**
     * @return SpyTaxSetQuery
     */
    public function queryAllTaxSets();

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return $this
     */
    public function joinTaxRates(ModelCriteria $expandableQuery);

}
