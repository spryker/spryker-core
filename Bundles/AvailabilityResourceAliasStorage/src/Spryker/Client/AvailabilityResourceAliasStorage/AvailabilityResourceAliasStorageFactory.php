<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityResourceAliasStorage;

use Spryker\Client\AvailabilityResourceAliasStorage\Storage\AvailabilityStorageReader;
use Spryker\Client\Kernel\AbstractFactory;

class AvailabilityResourceAliasStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\AvailabilityResourceAliasStorage\Storage\AvailabilityStorageReaderInterface
     */
    public function createAvailabilityStorageReader()
    {
        return new AvailabilityStorageReader(
            $this->getStorage(),
            $this->getSynchronizationService(),
            $this->getStore()
        );
    }

    /**
     * @return \Spryker\Client\AvailabilityResourceAliasStorage\Dependency\Client\AvailabilityResourceAliasStorageToStorageClientInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(AvailabilityResourceAliasStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\AvailabilityResourceAliasStorage\Dependency\Service\AvailabilityResourceAliasStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(AvailabilityResourceAliasStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(AvailabilityResourceAliasStorageDependencyProvider::STORE);
    }
}
