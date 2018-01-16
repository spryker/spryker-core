<?php

namespace Spryker\Zed\AvailabilityStorage\Business;

use Spryker\Zed\AvailabilityStorage\AvailabilityStorageDependencyProvider;
use Spryker\Zed\AvailabilityStorage\Business\Storage\AvailabilityStorage;
use Spryker\Zed\AvailabilityStorage\Business\Storage\AvailabilityStorageInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AvailabilityStorage\AvailabilityStorageConfig getConfig()
 * @method \Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageQueryContainer getQueryContainer()
 */
class AvailabilityStorageBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @param bool $sendingToQueue
     *
     * @return AvailabilityStorageInterface
     */
    public function createAvailabilityStorage($sendingToQueue = true)
    {
        return new AvailabilityStorage($this->getStore(), $this->getQueryContainer(), $sendingToQueue);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(AvailabilityStorageDependencyProvider::STORE);
    }
}
