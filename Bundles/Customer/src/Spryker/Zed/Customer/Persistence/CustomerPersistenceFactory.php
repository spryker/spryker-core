<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Persistence;

use Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;

/**
 * @method \Spryker\Zed\Customer\CustomerConfig getConfig()
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainer getQueryContainer()
 */
class CustomerPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function createSpyCustomerQuery()
    {
        return SpyCustomerQuery::create();
    }

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    public function createSpyCustomerAddressQuery()
    {
        return SpyCustomerAddressQuery::create();
    }

}
