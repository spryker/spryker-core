<?php

namespace Spryker\Zed\FileManagerStorage\Communication;

use Spryker\Zed\FileManagerStorage\Dependency\Facade\FileManagerStorageToEventBehaviorFacadeBridgeInterface;
use Spryker\Zed\FileManagerStorage\Dependency\Facade\FileManagerStorageToLocaleFacadeBridgeInterface;
use Spryker\Zed\FileManagerStorage\FileManagerStorageDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class FileManagerStorageCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return FileManagerStorageToEventBehaviorFacadeBridgeInterface
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(FileManagerStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return FileManagerStorageToLocaleFacadeBridgeInterface
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(FileManagerStorageDependencyProvider::FACADE_LOCALE);
    }

}