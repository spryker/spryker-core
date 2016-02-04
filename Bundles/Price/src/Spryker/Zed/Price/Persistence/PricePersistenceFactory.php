<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price\Persistence;

use Orm\Zed\Price\Persistence\SpyPriceProductQuery;
use Orm\Zed\Price\Persistence\SpyPriceTypeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Price\PriceConfig getConfig()
 * @method \Spryker\Zed\Price\Persistence\PriceQueryContainer getQueryContainer()
 */
class PricePersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Price\Persistence\SpyPriceTypeQuery
     */
    public function createPriceTypeQuery()
    {
        return SpyPriceTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function createPriceProductQuery()
    {
        return SpyPriceProductQuery::create();
    }

}
