<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesReturn\SalesReturnDependencyProvider;

/**
 * @method \Spryker\Zed\SalesReturn\SalesReturnConfig getConfig()
 * @method \Spryker\Zed\SalesReturn\Persistence\SalesReturnEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface getRepository()
 */
class SalesReturnPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery
     */
    public function getSalesReturnPropelQuery(): SpySalesReturnQuery
    {
        return SpySalesReturnQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSalesOrderItemPropelQuery(): SpySalesOrderItemQuery
    {
        return $this->getProvidedDependency(SalesReturnDependencyProvider::PROPEL_QUERY_SALES_ORDER_ITEM);
    }
}
