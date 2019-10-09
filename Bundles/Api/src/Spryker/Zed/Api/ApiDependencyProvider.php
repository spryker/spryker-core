<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Api\ApiConfig getConfig()
 */
class ApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_ENCODING = 'SERVICE_ENCODING';
    public const PLUGINS_API = 'PLUGINS_API';
    public const PLUGINS_API_VALIDATOR = 'PLUGINS_API_VALIDATOR';
    public const PLUGINS_API_REQUEST_TRANSFER_FILTER = 'PLUGINS_API_REQUEST_TRANSFER_FILTER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container[static::SERVICE_ENCODING] = function (Container $container) {
            return $container->getLocator()->utilEncoding()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->provideApiPlugins($container);
        $container = $this->provideApiValidatorPlugins($container);
        $container = $this->provideApiRequestTransferFilterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = parent::providePersistenceLayerDependencies($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideApiPlugins(Container $container)
    {
        $container[static::PLUGINS_API] = function (Container $container) {
            return $this->getApiResourcePluginCollection();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideApiValidatorPlugins(Container $container)
    {
        $container[static::PLUGINS_API_VALIDATOR] = function (Container $container) {
            return $this->getApiValidatorPluginCollection();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface[]
     */
    protected function getApiResourcePluginCollection()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\Api\Dependency\Plugin\ApiValidatorPluginInterface[]
     */
    protected function getApiValidatorPluginCollection()
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideApiRequestTransferFilterPlugins(Container $container): Container
    {
        /**
         * @return \Spryker\Zed\Api\Communication\Plugin\ApiRequestTransferFilterPluginInterface[]
         */
        $container[static::PLUGINS_API_REQUEST_TRANSFER_FILTER] = function (Container $container): array {
            return $this->getApiRequestTransferFilterPluginCollection();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Api\Communication\Plugin\ApiRequestTransferFilterPluginInterface[]
     */
    protected function getApiRequestTransferFilterPluginCollection(): array
    {
        return [];
    }
}
