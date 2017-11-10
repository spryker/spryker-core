<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Persistence;

use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Stock\StockConfig getConfig()
 * @method \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface getQueryContainer()
 */
class StockPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function createStockProductQuery()
    {
        return SpyStockProductQuery::create();
    }

    /**
     * @return \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    public function createStockQuery()
    {
        return SpyStockQuery::create();
    }
}
