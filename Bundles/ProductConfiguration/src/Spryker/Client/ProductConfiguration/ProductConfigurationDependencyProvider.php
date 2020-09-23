<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductConfiguration\Exception\MissingDefaultProductConfigurationRequestPluginException;
use Spryker\Client\ProductConfiguration\Exception\MissingDefaultProductConfiguratorResponsePluginException;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface;

/**
 * @method \Spryker\Client\ProductConfiguration\ProductConfigurationConfig getConfig()
 */
class ProductConfigurationDependencyProvider extends AbstractDependencyProvider
{
    public const PLUGINS_PRODUCT_CONFIGURATOR_REQUEST = 'PLUGINS_PRODUCT_CONFIGURATOR_REQUEST';
    public const PLUGIN_DEFAULT_PRODUCT_CONFIGURATOR_REQUEST = 'PLUGIN_DEFAULT_PRODUCT_CONFIGURATOR_REQUEST';

    public const PLUGINS_PRODUCT_CONFIGURATOR_RESPONSE = 'PLUGINS_PRODUCT_CONFIGURATOR_RESPONSE';
    public const PLUGIN_DEFAULT_PRODUCT_CONFIGURATOR_RESPONSE = 'PLUGIN_DEFAULT_PRODUCT_CONFIGURATOR_RESPONSE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addProductConfiguratorRequestPlugins($container);
        $container = $this->addDefaultProductConfiguratorRequestPlugin($container);
        $container = $this->addProductConfiguratorResponsePlugins($container);
        $container = $this->addDefaultProductConfiguratorResponsePlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductConfiguratorRequestPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_CONFIGURATOR_REQUEST, function () {
            return $this->getProductConfiguratorRequestPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductConfiguratorResponsePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_CONFIGURATOR_RESPONSE, function () {
            return $this->getProductConfiguratorResponsePlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface[]
     */
    protected function getProductConfiguratorRequestPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface[]
     */
    protected function getProductConfiguratorResponsePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addDefaultProductConfiguratorRequestPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_DEFAULT_PRODUCT_CONFIGURATOR_REQUEST, function () {
            return $this->getDefaultProductConfiguratorRequestPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addDefaultProductConfiguratorResponsePlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_DEFAULT_PRODUCT_CONFIGURATOR_RESPONSE, function () {
            return $this->getDefaultProductConfiguratorResponsePlugin();
        });

        return $container;
    }

    /**
     * @throws \Spryker\Client\ProductConfiguration\Exception\MissingDefaultProductConfigurationRequestPluginException
     *
     * @return \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface
     */
    protected function getDefaultProductConfiguratorRequestPlugin(): ProductConfiguratorRequestPluginInterface
    {
        throw new MissingDefaultProductConfigurationRequestPluginException(
            sprintf(
                "Missing instance of %s! You need to provide default product configurator request plugin
                      in your own ProductConfigurationDependencyProvider::getDefaultProductConfiguratorRequestPlugin().",
                ProductConfiguratorRequestPluginInterface::class
            )
        );
    }

    /**
     * @throws \Spryker\Client\ProductConfiguration\Exception\MissingDefaultProductConfiguratorResponsePluginException
     *
     * @return \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface
     */
    protected function getDefaultProductConfiguratorResponsePlugin(): ProductConfiguratorResponsePluginInterface
    {
        throw new MissingDefaultProductConfiguratorResponsePluginException(
            sprintf(
                "Missing instance of %s! You need to provide default product configurator response plugin
                      in your own ProductConfigurationDependencyProvider::getDefaultProductConfiguratorResponsePlugin().",
                ProductConfiguratorResponsePluginInterface::class
            )
        );
    }
}