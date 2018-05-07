<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Persistence;

use Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery;
use Orm\Zed\CustomerAccessStorage\Persistence\SpyUnauthenticatedCustomerAccessStorageQuery;
use Spryker\Zed\CustomerAccessStorage\CustomerAccessStorageDependencyProvider;
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
        return $this->getProvidedDependency(CustomerAccessStorageDependencyProvider::QUERY_CUSTOMER_ACCESS);
    }

    /**
     * @return \Orm\Zed\CustomerAccessStorage\Persistence\SpyUnauthenticatedCustomerAccessStorageQuery
     */
    public function createPropelCustomerAccessStorageQuery(): SpyUnauthenticatedCustomerAccessStorageQuery
    {
        return SpyUnauthenticatedCustomerAccessStorageQuery::create();
    }
}
