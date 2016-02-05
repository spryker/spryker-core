<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Tax\Persistence;

use Orm\Zed\Tax\Persistence\SpyTaxRateQuery;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Tax\TaxConfig getConfig()
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainer getQueryContainer()
 */
class TaxPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Tax\Persistence\SpyTaxRateQuery
     */
    public function createTaxRateQuery()
    {
        return SpyTaxRateQuery::create();
    }

    /**
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function createTaxSetQuery()
    {
        return SpyTaxSetQuery::create();
    }

}
