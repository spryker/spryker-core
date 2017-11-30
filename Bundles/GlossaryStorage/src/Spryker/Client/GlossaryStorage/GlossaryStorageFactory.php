<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlossaryStorage;

use Spryker\Client\GlossaryStorage\Storage\AvailabilityKeyValueStorage;
use Spryker\Client\Kernel\AbstractFactory;

class GlossaryStorageFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\GlossaryStorage\Storage\AvailabilityKeyValueStorageInterface
     */
    public function createAvailabilityKeyValueStorage()
    {
        return new AvailabilityKeyValueStorage($this->getStorage(), $this->getSynchronizationService());
    }

    /**
     * @return \Spryker\Client\GlossaryStorage\Dependency\Client\GlossaryStorageToStorageInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(GlossaryStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(GlossaryStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

}
