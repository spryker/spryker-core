<?php

namespace Spryker\Zed\CustomerAccess\Persistence;

interface CustomerAccessQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery
     */
    public function queryCustomerAccess();
}