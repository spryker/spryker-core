<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\EntityTag;

use Spryker\Client\EntityTag\Dependency\Client\EntityTagToStorageClientBridge;
use Spryker\Client\EntityTag\Dependency\Service\EntityTagToSynchronizationServiceBridge;
use Spryker\Client\EntityTag\Dependency\Service\EntityTagToUtilEncodingServiceBridge;
use Spryker\Client\EntityTag\Dependency\Service\EntityTagToUtilTextServiceBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

/**
 * @method \Spryker\Client\EntityTag\EntityTagConfig getConfig()
 */
class EntityTagDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addStorageClient($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addUtilTextService($container);
        $container = $this->addSynchronizationService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container[static::CLIENT_STORAGE] = function (Container $container) {
            return new EntityTagToStorageClientBridge(
                $container->getLocator()->storage()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new EntityTagToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilTextService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_TEXT] = function (Container $container) {
            return new EntityTagToUtilTextServiceBridge(
                $container->getLocator()->utilText()->service()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSynchronizationService(Container $container): Container
    {
        $container[static::SERVICE_SYNCHRONIZATION] = function (Container $container) {
            return new EntityTagToSynchronizationServiceBridge(
                $container->getLocator()->synchronization()->service()
            );
        };

        return $container;
    }
}
