<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Persistence;

use Orm\Zed\Price\Persistence\SpyPriceProductQuery;
use Orm\Zed\Price\Persistence\SpyPriceTypeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Price\PriceConfig getConfig()
 * @method \Spryker\Zed\Price\Persistence\PriceQueryContainerInterface getQueryContainer()
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
