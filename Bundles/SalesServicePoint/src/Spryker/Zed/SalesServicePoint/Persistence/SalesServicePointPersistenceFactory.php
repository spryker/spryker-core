<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Persistence;

use Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePointQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesServicePoint\Persistence\Propel\Mapper\SalesServicePointMapper;

/**
 * @method \Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesServicePoint\SalesServicePointConfig getConfig()
 */
class SalesServicePointPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePointQuery
     */
    public function getSalesOrderItemServicePointQuery(): SpySalesOrderItemServicePointQuery
    {
        return SpySalesOrderItemServicePointQuery::create();
    }

    /**
     * @return \Spryker\Zed\SalesServicePoint\Persistence\Propel\Mapper\SalesServicePointMapper
     */
    public function createSalesServicePointMapper(): SalesServicePointMapper
    {
        return new SalesServicePointMapper();
    }
}
