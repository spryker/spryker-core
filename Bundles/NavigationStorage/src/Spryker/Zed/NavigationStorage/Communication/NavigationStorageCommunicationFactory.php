<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\NavigationStorage\Dependency\Facade\NavigationStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\NavigationStorage\Dependency\Service\NavigationStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\NavigationStorage\NavigationStorageDependencyProvider;

/**
 * @method \Spryker\Zed\NavigationStorage\Persistence\NavigationStorageQueryContainer getQueryContainer()
 * @method \Spryker\Zed\NavigationStorage\NavigationStorageConfig getConfig()
 */
class NavigationStorageCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return NavigationStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(NavigationStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return NavigationStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(NavigationStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\NavigationStorage\Dependency\Facade\NavigationStorageToNavigationInterface
     */
    public function getNavigationFacade()
    {
        return $this->getProvidedDependency(NavigationStorageDependencyProvider::FACADE_NAVIGATION);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(NavigationStorageDependencyProvider::STORE);
    }

}
