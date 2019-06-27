<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SetupFrontend\Dependency\Service\SetupFrontendToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\SetupFrontend\SetupFrontendConfig getConfig()
 */
class SetupFrontendDependencyProvider extends AbstractBundleDependencyProvider
{
    public const STORE_NAME = 'STORE_NAME';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const PLUGINS_YVES_FRONTEND_STORE_CONFIG_EXPANDER = 'PLUGINS_YVES_FRONTEND_STORE_CONFIG_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addStoreName($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addYvesFrontendStoreConfigExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addStoreName($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreName(Container $container): Container
    {
        $container->set(static::STORE_NAME, function () {
            return Store::getInstance()->getStoreName();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new SetupFrontendToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addYvesFrontendStoreConfigExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_YVES_FRONTEND_STORE_CONFIG_EXPANDER, function () {
            return $this->getYvesFrontendStoreConfigExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\SetupFrontendExtension\Dependency\Plugin\YvesFrontendStoreConfigExpanderPluginInterface[]
     */
    protected function getYvesFrontendStoreConfigExpanderPlugins(): array
    {
        return [];
    }
}
