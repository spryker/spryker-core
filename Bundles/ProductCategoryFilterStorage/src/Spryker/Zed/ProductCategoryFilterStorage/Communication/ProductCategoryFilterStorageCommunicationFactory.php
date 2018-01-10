<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductCategoryFilterStorage\ProductCategoryFilterStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCategoryFilterStorage\Persistence\ProductCategoryFilterStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductCategoryFilterStorage\ProductCategoryFilterStorageConfig getConfig()
 */
class ProductCategoryFilterStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductCategoryFilterStorage\Dependency\Service\ProductCategoryFilterStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(ProductCategoryFilterStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterStorage\Dependency\Service\ProductCategoryFilterStorageToUtilEncodingInterface
     */
    public function getUtilEncoding()
    {
        return $this->getProvidedDependency(ProductCategoryFilterStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterStorage\Dependency\Facade\ProductCategoryFilterStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductCategoryFilterStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductCategoryFilterStorageDependencyProvider::STORE);
    }
}
