<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOms\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesOms\Persistence\Mapper\SalesOmsMapper;

/**
 * @method \Spryker\Zed\SalesOms\SalesOmsConfig getConfig()
 * @method \Spryker\Zed\SalesOms\Persistence\SalesOmsQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\SalesOms\Persistence\SalesOmsRepositoryInterface getRepository()
 */
class SalesOmsPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSalesOrderItemPropelQuery(): SpySalesOrderItemQuery
    {
        return SpySalesOrderItemQuery::create();
    }

    /**
     * @return \Spryker\Zed\SalesOms\Persistence\Mapper\SalesOmsMapper
     */
    public function createSalesOmsMapper(): SalesOmsMapper
    {
        return new SalesOmsMapper();
    }
}
