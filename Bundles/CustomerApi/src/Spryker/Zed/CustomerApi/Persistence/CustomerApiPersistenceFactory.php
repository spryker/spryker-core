<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Persistence;

use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Spryker\Zed\CustomerApi\CustomerApiDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CustomerApi\CustomerApiConfig getConfig()
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainer getQueryContainer()
 */
class CustomerApiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function createCustomerQuery()
    {
        return SpyCustomerQuery::create();
    }

    /**
     * @return \Spryker\Zed\Api\Persistence\ApiQueryContainerInterface
     */
    public function getApiQueryContainer()
    {
        return $this->getProvidedDependency(CustomerApiDependencyProvider::QUERY_CONTAINER_API);
    }
}
