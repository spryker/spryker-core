<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ResourceShare\Dependency\Service\ResourceShareToUtilEncodingServiceBridge;
use Spryker\Zed\ResourceShare\Dependency\Service\ResourceShareToUtilUuidGeneratorServiceBridge;

class ResourceShareDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const SERVICE_UTIL_UUID_GENERATOR = 'SERVICE_UTIL_UUID_GENERATOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addUtilUuidService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new ResourceShareToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilUuidService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_UUID_GENERATOR] = function (Container $container) {
            return new ResourceShareToUtilUuidGeneratorServiceBridge(
                $container->getLocator()->utilUuidGenerator()->service()
            );
        };

        return $container;
    }
}
