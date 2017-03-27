<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business;

use Spryker\Zed\CustomerApi\Business\Model\CustomerApi;
use Spryker\Zed\CustomerApi\CustomerApiDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CustomerApi\CustomerApiConfig getConfig()
 * @method \Spryker\Zed\CustomerApi\Persistence\CustomerApiQueryContainer getQueryContainer()
 */
class CustomerApiBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\CustomerApi\Business\Model\CustomerApi
     */
    public function createCustomerApi()
    {
        return new CustomerApi(
            $this->getApiQueryContainer(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiInterface
     */
    protected function getApiQueryContainer()
    {
        return $this->getProvidedDependency(CustomerApiDependencyProvider::QUERY_CONTAINER_API);
    }

}
