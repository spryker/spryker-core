<?php

namespace Spryker\Zed\CustomerGroup\Dependency\QueryContainer;


use Orm\Zed\Customer\Persistence\SpyCustomerQuery;

interface CustomerGroupToCustomerQueryContainerInterface
{

    /**
     * @return SpyCustomerQuery
     */
    public function queryCustomers();

}