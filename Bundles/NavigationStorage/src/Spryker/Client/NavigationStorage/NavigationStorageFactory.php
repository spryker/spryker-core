<?php

namespace Spryker\Client\NavigationStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\NavigationStorage\Storage\NavigationStorage;
use Spryker\Client\NavigationStorage\Storage\NavigationStorageInterface;

class NavigationStorageFactory extends AbstractFactory
{

    /**
     * @return NavigationStorageInterface
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
