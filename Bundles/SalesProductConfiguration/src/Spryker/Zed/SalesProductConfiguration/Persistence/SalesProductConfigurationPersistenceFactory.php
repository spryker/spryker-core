<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfiguration\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesProductConfiguration\Persistence\Propel\Mapper\SalesOrderItemConfigurationMapper;

/**
 * @method \Spryker\Zed\SalesProductConfiguration\SalesProductConfigurationConfig getConfig()
 * @method \Spryker\Zed\SalesProductConfiguration\Persistence\SalesProductConfigurationEntityManagerInterface getEntityManager()
 */
class SalesProductConfigurationPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\SalesProductConfiguration\Persistence\Propel\Mapper\SalesOrderItemConfigurationMapper
     */
    public function createSalesOrderItemConfigurationMapper(): SalesOrderItemConfigurationMapper
    {
        return new SalesOrderItemConfigurationMapper();
    }
}
