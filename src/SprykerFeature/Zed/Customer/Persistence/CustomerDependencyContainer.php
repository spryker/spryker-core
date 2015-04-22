<?php

namespace SprykerFeature\Zed\Customer\Persistence;

use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerAddressQuery;
use SprykerEngine\Zed\Kernel\Persistence\AbstractDependencyContainer;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerQuery;

class CustomerDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return SpyCustomerQuery
     */
    public function createSpyCustomerQuery()
    {
        return SpyCustomerQuery::create();
    }

    /**
     * @return SpyCustomerAddressQuery
     */
    public function createSpyCustomerAddressQuery()
    {
        return SpyCustomerAddressQuery::create();
    }
}
