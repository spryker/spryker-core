<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Persistence;

use Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery;
use SprykerEngine\Zed\Kernel\Persistence\AbstractPersistenceDependencyContainer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;

class CustomerDependencyContainer extends AbstractPersistenceDependencyContainer
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
