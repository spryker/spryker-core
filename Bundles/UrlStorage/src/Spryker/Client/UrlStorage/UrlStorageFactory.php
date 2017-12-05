<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\UrlStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\UrlStorage\Storage\UrlStorageReader;

class UrlStorageFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\UrlStorage\Storage\UrlStorageReaderInterface
     */
    public function createUrlStorageReader()
    {
        return new UrlStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getStore(),
            $this->getUrlStorageResourceMapperPlugins()
        );
    }

    /**
     * @return \Spryker\Client\UrlStorage\Dependency\Client\UrlStorageToStorageInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\UrlStorage\Dependency\Service\UrlStorageToSynchronizationServiceInterface
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\UrlStorage\Dependency\Plugin\UrlStorageResourceMapperPluginInterface[]
     */
    protected function getUrlStorageResourceMapperPlugins()
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::PLUGINS_URL_STORAGE_RESOURCE_MAPPER);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::STORE);
    }

}
