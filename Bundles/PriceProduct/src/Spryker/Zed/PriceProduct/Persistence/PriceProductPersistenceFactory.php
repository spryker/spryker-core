<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\PriceProduct\PriceProductConfig getConfig()
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainer getQueryContainer()
 */
class PriceProductPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery
     */
    public function createPriceTypeQuery()
    {
        return SpyPriceTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function createPriceProductQuery()
    {
        return SpyPriceProductQuery::create();
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    public function createPriceProductStoreQuery()
    {
        return SpyPriceProductStoreQuery::create();
    }
}
