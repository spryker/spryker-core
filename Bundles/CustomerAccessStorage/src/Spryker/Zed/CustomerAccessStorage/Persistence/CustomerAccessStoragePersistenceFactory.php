<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Persistence;

use Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery;
use Orm\Zed\CustomerAccessStorage\Persistence\SpyUnauthenticatedCustomerAccessStorageQuery;
use Spryker\Zed\CustomerAccessStorage\Persistence\Propel\Mapper\CustomerAccessMapper;
use Spryker\Zed\CustomerAccessStorage\Persistence\Propel\Mapper\CustomerAccessMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CustomerAccessStorage\CustomerAccessStorageConfig getConfig()
 * @method \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageQueryContainerInterface getQueryContainer()
 */
class CustomerAccessStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery
     */
    public function createPropelCustomerAccessQuery(): SpyUnauthenticatedCustomerAccessQuery
    {
        return SpyUnauthenticatedCustomerAccessQuery::create();
    }

    /**
     * @return \Orm\Zed\CustomerAccessStorage\Persistence\SpyUnauthenticatedCustomerAccessStorageQuery
     */
    public function createPropelCustomerAccessStorageQuery(): SpyUnauthenticatedCustomerAccessStorageQuery
    {
        return SpyUnauthenticatedCustomerAccessStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\CustomerAccessStorage\Persistence\Propel\Mapper\CustomerAccessMapperInterface
     */
    public function createCustomerAccessMapper(): CustomerAccessMapperInterface
    {
        return new CustomerAccessMapper();
    }
}
