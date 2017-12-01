<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Persistence;

use Orm\Zed\Tax\Persistence\SpyTaxRateQuery;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Tax\TaxConfig getConfig()
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface getQueryContainer()
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
