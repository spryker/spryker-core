<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\NavigationStorage\Business\Storage\NavigationStorageWriter;
use Spryker\Zed\NavigationStorage\NavigationStorageDependencyProvider;

/**
 * @method \Spryker\Zed\NavigationStorage\NavigationStorageConfig getConfig()
 * @method \Spryker\Zed\NavigationStorage\Persistence\NavigationStorageQueryContainerInterface getQueryContainer()
 */
class NavigationStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\NavigationStorage\Business\Storage\NavigationStorageWriterInterface
     */
    public function createNavigationStorageWriter()
    {
        return new NavigationStorageWriter(
            $this->getUtilSanitizeService(),
            $this->getNavigationFacade(),
            $this->getQueryContainer(),
            $this->getStore(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\NavigationStorage\Dependency\Service\NavigationStorageToUtilSanitizeServiceInterface
     */
    protected function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(NavigationStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Spryker\Zed\NavigationStorage\Dependency\Facade\NavigationStorageToNavigationInterface
     */
    protected function getNavigationFacade()
    {
        return $this->getProvidedDependency(NavigationStorageDependencyProvider::FACADE_NAVIGATION);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(NavigationStorageDependencyProvider::STORE);
    }
}
