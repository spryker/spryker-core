<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Persistence;

use Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery;
use Orm\Zed\CustomerAccessStorage\Persistence\SpyUnauthenticatedCustomerAccessStorageQuery;
use Spryker\Zed\CustomerAccessStorage\CustomerAccessStorageDependencyProvider;
use Spryker\Zed\CustomerAccessStorage\Persistence\Propel\Mapper\CustomerAccessStorageMapper;
use Spryker\Zed\CustomerAccessStorage\Persistence\Propel\Mapper\CustomerAccessStorageMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CustomerAccessStorage\CustomerAccessStorageConfig getConfig()
 */
class CustomerAccessStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery
     */
    public function createPropelCustomerAccessQuery(): SpyUnauthenticatedCustomerAccessQuery
    {
        return $this->getProvidedDependency(CustomerAccessStorageDependencyProvider::PROPEL_QUERY_CUSTOMER_ACCESS);
    }

    /**
     * @return \Orm\Zed\CustomerAccessStorage\Persistence\SpyUnauthenticatedCustomerAccessStorageQuery
     */
    public function createCustomerAccessStorageQuery(): SpyUnauthenticatedCustomerAccessStorageQuery
    {
        return SpyUnauthenticatedCustomerAccessStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\CustomerAccessStorage\Persistence\Propel\Mapper\CustomerAccessStorageMapperInterface
     */
    public function createCustomerAccessStorageMapper(): CustomerAccessStorageMapperInterface
    {
        return new CustomerAccessStorageMapper();
    }
}
