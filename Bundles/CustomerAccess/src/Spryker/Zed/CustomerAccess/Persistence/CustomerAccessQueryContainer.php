<?php

namespace Spryker\Zed\CustomerAccess\Persistence;

use Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery;

class CustomerAccessQueryContainer implements CustomerAccessQueryContainerInterface
{
    /**
     * @return \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery
     */
    public function queryCustomerAccess()
    {
        return new SpyUnauthenticatedCustomerAccessQuery();
    }
}