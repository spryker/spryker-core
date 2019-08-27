<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage;

use Spryker\Zed\CmsBlockProductStorage\Dependency\Client\CmsBlockProductStorageToStorageClientBridge;
use Spryker\Zed\CmsBlockProductStorage\Dependency\Facade\CmsBlockProductStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\CmsBlockProductStorage\Dependency\QueryContainer\CmsBlockProductStorageToCmsBlockProductConnectorQueryContainerBridge;
use Spryker\Zed\CmsBlockProductStorage\Dependency\Service\CmsBlockProductStorageToSynchronizationServiceBridge;
use Spryker\Zed\CmsBlockProductStorage\Dependency\Service\CmsBlockProductStorageToUtilSanitizeServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CmsBlockProductStorage\CmsBlockProductStorageConfig getConfig()
 */
class CmsBlockProductStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';
    public const QUERY_CONTAINER_CMS_BLOCK_PRODUCT_CONNECTOR = 'QUERY_CONTAINER_CMS_BLOCK_PRODUCT_CONNECTOR';
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addFacadeEventBehavior($container);
        $container = $this->addClientStorage($container);
        $container = $this->addServiceSynchronization($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::SERVICE_UTIL_SANITIZE] = function (Container $container) {
            return new CmsBlockProductStorageToUtilSanitizeServiceBridge($container->getLocator()->utilSanitize()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[static::QUERY_CONTAINER_CMS_BLOCK_PRODUCT_CONNECTOR] = function (Container $container) {
            return new CmsBlockProductStorageToCmsBlockProductConnectorQueryContainerBridge($container->getLocator()->cmsBlockProductConnector()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadeEventBehavior(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new CmsBlockProductStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addClientStorage(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE, function (Container $container) {
            return new CmsBlockProductStorageToStorageClientBridge($container->getLocator()->storage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addServiceSynchronization(Container $container): Container
    {
        $container->set(self::SERVICE_SYNCHRONIZATION, function (Container $container) {
            return new CmsBlockProductStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        });

        return $container;
    }
}
