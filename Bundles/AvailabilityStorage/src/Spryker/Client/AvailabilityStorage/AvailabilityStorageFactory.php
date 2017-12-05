<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityStorage;

use Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReader;
use Spryker\Client\Kernel\AbstractFactory;

class AvailabilityStorageFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReaderInterface
     */
    public function createAvailabilityKeyValueStorage()
    {
        return new AvailabilityStorageReader($this->getStorage(), $this->getSynchronizationService());
    }

    /**
     * @return \Spryker\Client\AvailabilityStorage\Dependency\Client\AvailabilityStorageToStorageInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(AvailabilityStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\AvailabilityStorage\Dependency\Service\AvailabilityStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(AvailabilityStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

}
