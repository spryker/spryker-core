<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\NavigationStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\NavigationStorage\Storage\NavigationStorage;

class NavigationStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\NavigationStorage\Storage\NavigationStorageInterface
     */
    public function createNavigationStorage()
    {
        return new NavigationStorage(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\NavigationStorage\Dependency\Client\NavigationStorageToStorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(NavigationStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\NavigationStorage\Dependency\Service\NavigationStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(NavigationStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
