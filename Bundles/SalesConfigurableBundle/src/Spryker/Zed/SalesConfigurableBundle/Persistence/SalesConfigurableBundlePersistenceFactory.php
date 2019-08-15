<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Persistence;

use Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesConfigurableBundle\Persistence\Propel\Mapper\SalesOrderConfiguredBundleMapper;

/**
 * @method \Spryker\Zed\SalesConfigurableBundle\SalesConfigurableBundleConfig getConfig()
 * @method \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface getRepository()
 */
class SalesConfigurableBundlePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleQuery
     */
    public function getSalesOrderConfiguredBundlePropelQuery(): SpySalesOrderConfiguredBundleQuery
    {
        return SpySalesOrderConfiguredBundleQuery::create();
    }

    /**
     * @return \Spryker\Zed\SalesConfigurableBundle\Persistence\Propel\Mapper\SalesOrderConfiguredBundleMapper
     */
    public function createSalesOrderConfiguredBundleMapper(): SalesOrderConfiguredBundleMapper
    {
        return new SalesOrderConfiguredBundleMapper();
    }
}
