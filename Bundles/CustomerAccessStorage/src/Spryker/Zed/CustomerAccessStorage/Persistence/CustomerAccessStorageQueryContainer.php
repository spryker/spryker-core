<?php

namespace Spryker\Zed\CustomerAccessStorage\Persistence;

use Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery;
use Orm\Zed\CustomerAccessStorage\Persistence\SpyUnauthenticatedCustomerAccessStorageQuery;

class CustomerAccessStorageQueryContainer implements CustomerAccessStorageQueryContainerInterface
{
    /**
     * @return \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery
     */
    public function queryCustomerAccess()
    {
        return new SpyUnauthenticatedCustomerAccessQuery();
    }

    /**
     *
     * @return \Orm\Zed\CustomerAccessStorage\Persistence\SpyUnauthenticatedCustomerAccessStorageQuery
     */
    public function queryCustomerAccessStorage()
    {
        return new SpyUnauthenticatedCustomerAccessStorageQuery();
    }
}
