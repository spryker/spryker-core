<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlossaryStorage;

use Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToUtilEncodingServiceInterface;
use Spryker\Client\GlossaryStorage\Processor\Mapper\GlossaryStorageMapper;
use Spryker\Client\GlossaryStorage\Processor\Mapper\GlossaryStorageMapperInterface;
use Spryker\Client\GlossaryStorage\Storage\GlossaryStorage;
use Spryker\Client\Kernel\AbstractFactory;

class GlossaryStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\GlossaryStorage\Storage\GlossaryStorageInterface
     */
    public function createTranslator()
    {
        return new GlossaryStorage(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getUtilEncodingService(),
            $this->createGlossaryStorageMapper()
        );
    }

    /**
     * @return \Spryker\Client\GlossaryStorage\Dependency\Client\GlossaryStorageToStorageClientInterface
     */
    protected function getStorageClient()
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

    /**
     * @return \Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): GlossaryStorageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(GlossaryStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Client\GlossaryStorage\Processor\Mapper\GlossaryStorageMapperInterface
     */
    protected function createGlossaryStorageMapper(): GlossaryStorageMapperInterface
    {
        return new GlossaryStorageMapper();
    }
}
