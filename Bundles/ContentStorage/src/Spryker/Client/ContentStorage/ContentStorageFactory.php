<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage;

use Spryker\Client\ContentStorage\Dependency\Client\ContentStorageToStorageClientInterface;
use Spryker\Client\ContentStorage\Dependency\Service\ContentStorageToSynchronizationServiceInterface;
use Spryker\Client\ContentStorage\Resolver\ContentResolver;
use Spryker\Client\ContentStorage\Resolver\ContentResolverInterface;
use Spryker\Client\ContentStorage\Storage\ContentStorage;
use Spryker\Client\ContentStorage\Storage\ContentStorageInterface;
use Spryker\Client\Kernel\AbstractFactory;

class ContentStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ContentStorage\Storage\ContentStorageInterface
     */
    public function createContentStorage(): ContentStorageInterface
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
    public function createContentResolver(): ContentResolverInterface
    {
        return new ContentResolver($this->getContentPlugins());
    }

    /**
     * @return \Spryker\Client\ContentStorage\Dependency\Client\ContentStorageToStorageClientInterface
     */
    public function getStorageClient(): ContentStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ContentStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ContentStorage\Dependency\Service\ContentStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ContentStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ContentStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ContentStorageExtension\Plugin\ContentTermExecutorPluginInterface[]
     */
    public function getContentPlugins(): array
    {
        return $this->getProvidedDependency(ContentStorageDependencyProvider::PLUGINS_CONTENT_ITEM);
    }
}
