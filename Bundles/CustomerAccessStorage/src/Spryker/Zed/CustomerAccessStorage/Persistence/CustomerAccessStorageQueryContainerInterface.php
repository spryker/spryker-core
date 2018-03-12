<?php

namespace Spryker\Zed\CustomerAccessStorage\Persistence;

interface CustomerAccessStorageQueryContainerInterface
{
    /**
     * @return \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery
     */
    public function queryCustomerAccess();

    /**
     * @return \Orm\Zed\CustomerAccessStorage\Persistence\SpyUnauthenticatedCustomerAccessStorageQuery
     */
    public function queryCustomerAccessStorage();
}