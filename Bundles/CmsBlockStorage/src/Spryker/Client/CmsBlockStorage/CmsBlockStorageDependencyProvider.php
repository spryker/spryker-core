<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockStorage;

use Spryker\Client\CmsBlockStorage\Dependency\Client\CmsBlockStorageToStorageBridge;
use Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToSynchronizationServiceBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CmsBlockStorageDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';
    public const PLUGINS_CMS_BLOCK_STORAGE_BLOCKS_FINDER = 'PLUGINS_CMS_BLOCK_STORAGE_BLOCKS_FINDER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addStorageClient($container);
        $container = $this->addSynchronizationService($container);
        $container = $this->addCmsBlockStorageBlocksFinderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE, function (Container $container) {
            return new CmsBlockStorageToStorageBridge($container->getLocator()->storage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSynchronizationService(Container $container): Container
    {
        $container->set(static::SERVICE_SYNCHRONIZATION, function (Container $container) {
            return new CmsBlockStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCmsBlockStorageBlocksFinderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CMS_BLOCK_STORAGE_BLOCKS_FINDER, function () {
            return $this->getCmsBlockStorageBlocksFinderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\CmsBlockStorageExtension\Dependency\Plugin\CmsBlockStorageBlocksFinderPluginInterface[]
     */
    protected function getCmsBlockStorageBlocksFinderPlugins(): array
    {
        return [];
    }
}
