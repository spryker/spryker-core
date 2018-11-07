<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi\Persistence;

use Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery;
use Spryker\Zed\CustomersRestApi\CustomersRestApiDependencyProvider;
use Spryker\Zed\CustomersRestApi\Persistence\Mapper\CustomerAddressMapper;
use Spryker\Zed\CustomersRestApi\Persistence\Mapper\CustomerAddressMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CustomersRestApi\CustomersRestApiConfig getConfig()
 */
class CustomersRestApiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\CustomersRestApi\Persistence\Mapper\CustomerAddressMapperInterface
     */
    public function createCustomerAddressMapper(): CustomerAddressMapperInterface
    {
        return new CustomerAddressMapper();
    }

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    public function getAddressesPropelQuery(): SpyCustomerAddressQuery
    {
        return $this->getProvidedDependency(CustomersRestApiDependencyProvider::PROPEL_QUERY_CUSTOMER_ADDRESS);
    }
}
