<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage;

use Spryker\Client\ContentStorage\Resolver\ContentResolver;
use Spryker\Client\ContentStorage\Storage\ContentStorage;
use Spryker\Client\Kernel\AbstractFactory;

class ContentStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ContentStorage\Storage\ContentStorageInterface
     */
    public function createContentStorage()
    {
        return new ContentStorage(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->createContentResolver()
        );
    }

    /**
     * @return \Spryker\Client\ContentStorage\Resolver\ContentResolverInterface
     */
    public function createContentResolver()
    {
        return new ContentResolver($this->getContentItemPlugins());
    }

    /**
     * @return \Spryker\Client\ContentStorage\Dependency\Client\ContentStorageToStorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(ContentStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ContentStorage\Dependency\Service\ContentStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(ContentStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ContentStorageExtension\Plugin\ContentTermExecutorPluginInterface[]
     */
    protected function getContentItemPlugins()
    {
        return $this->getProvidedDependency(ContentStorageDependencyProvider::PLUGIN_CONTENT_ITEM_PLUGINS);
    }
}
