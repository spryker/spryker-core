<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence;

use Orm\Zed\ConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleQuery;
use Spryker\Zed\ConfigurableBundle\Persistence\Propel\Mapper\SalesOrderConfiguredBundleMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ConfigurableBundle\ConfigurableBundleConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface getRepository()
 */
class ConfigurableBundlePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleQuery
     */
    public function getSalesOrderConfiguredBundlePropelQuery(): SpySalesOrderConfiguredBundleQuery
    {
        return SpySalesOrderConfiguredBundleQuery::create();
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Persistence\Propel\Mapper\SalesOrderConfiguredBundleMapper
     */
    public function createSalesOrderConfiguredBundleMapper(): SalesOrderConfiguredBundleMapper
    {
        return new SalesOrderConfiguredBundleMapper();
    }
}
