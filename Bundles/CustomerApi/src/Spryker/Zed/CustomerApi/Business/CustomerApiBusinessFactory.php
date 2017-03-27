<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business;

use Spryker\Zed\CustomerApi\CustomerApiDependencyProvider;
use Spryker\Zed\CustomerApi\Business\Model\CustomerApi;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CustomerGroup\CustomerGroupConfig getConfig()
 * @method \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainer getQueryContainer()
 */
class CustomerApiBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\CustomerApi\Business\Model\CustomerApi
     */
    public function createCustomerApi()
    {
        return new CustomerApi($this->getCustomerQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    protected function getCustomerQueryContainer()
    {
        return $this->getProvidedDependency(CustomerApiDependencyProvider::QUERY_CONTAINER_CUSTOMER);
    }

}
