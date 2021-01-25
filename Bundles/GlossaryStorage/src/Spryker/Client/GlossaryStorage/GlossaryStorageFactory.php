<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlossaryStorage;

use Spryker\Client\GlossaryStorage\Dependency\Client\GlossaryStorageToStorageClientInterface;
use Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToSynchronizationServiceInterface;
use Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToUtilEncodingServiceInterface;
use Spryker\Client\GlossaryStorage\Storage\GlossaryStorageReader;
use Spryker\Client\Kernel\AbstractFactory;

class GlossaryStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\GlossaryStorage\Storage\GlossaryStorageReaderInterface
     */
    public function createTranslator()
    {
        return new GlossaryStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Client\GlossaryStorage\Dependency\Client\GlossaryStorageToStorageClientInterface
     */
    public function getStorageClient(): GlossaryStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(GlossaryStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): GlossaryStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(GlossaryStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): GlossaryStorageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(GlossaryStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
