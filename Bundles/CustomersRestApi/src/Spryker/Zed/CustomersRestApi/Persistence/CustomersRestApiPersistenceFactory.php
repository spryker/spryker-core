<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi\Persistence;

use Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery;
use Spryker\Glue\CustomersRestApi\CustomersRestApiDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CustomersRestApi\CustomersRestApiConfig getConfig()
 */
class CustomersRestApiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    public function getAddressesQuery(): SpyCustomerAddressQuery
    {
        return $this->getProvidedDependency(CustomersRestApiDependencyProvider::SPY_CUSTOMER_ADDRESS);
    }
}
