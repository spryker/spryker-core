<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Communication;

use Spryker\Zed\CategoryStorage\CategoryStorageDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CategoryStorage\CategoryStorageConfig getConfig()
 */
class CategoryStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(CategoryStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(CategoryStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(CategoryStorageDependencyProvider::STORE);
    }
}
