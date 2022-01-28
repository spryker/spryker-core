<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api;

use Spryker\Zed\Api\Dependency\Service\ApiToUtilEncodingServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Api\ApiConfig getConfig()
 */
class ApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_ENCODING = 'SERVICE_ENCODING';

    /**
     * @var string
     */
    public const PLUGINS_API = 'PLUGINS_API';

    /**
     * @var string
     */
    public const PLUGINS_API_VALIDATOR = 'PLUGINS_API_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_API_REQUEST_TRANSFER_FILTER = 'PLUGINS_API_REQUEST_TRANSFER_FILTER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addApiResourcePlugins($container);
        $container = $this->addApiValidatorPlugins($container);
        $container = $this->addApiRequestTransferFilterPlugins($container);

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

        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_ENCODING, function (Container $container) {
            return new ApiToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addApiResourcePlugins(Container $container)
    {
        $container->set(static::PLUGINS_API, function () {
            return $this->getApiResourcePluginCollection();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addApiValidatorPlugins(Container $container)
    {
        $container->set(static::PLUGINS_API_VALIDATOR, function () {
            return $this->getApiValidatorPluginCollection();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface>
     */
    protected function getApiResourcePluginCollection()
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\ApiExtension\Dependency\Plugin\ApiValidatorPluginInterface>
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
    protected function addApiRequestTransferFilterPlugins(Container $container): Container
    {
        /**
         * @return array<\Spryker\Zed\Api\Communication\Plugin\ApiRequestTransferFilterPluginInterface>
         */
        $container->set(static::PLUGINS_API_REQUEST_TRANSFER_FILTER, function (): array {
            return $this->getApiRequestTransferFilterPluginCollection();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\Api\Communication\Plugin\ApiRequestTransferFilterPluginInterface>
     */
    protected function getApiRequestTransferFilterPluginCollection(): array
    {
        return [];
    }
}
