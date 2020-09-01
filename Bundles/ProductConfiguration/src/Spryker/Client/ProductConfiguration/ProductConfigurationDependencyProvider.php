<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductConfiguration\Exception\MissingProductConfigurationRequestDefaultPluginException;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfigurationExtensionRequestPluginInterface;

/**
 * @method \Spryker\Client\ProductConfiguration\ProductConfigurationConfig getConfig()
 */
class ProductConfigurationDependencyProvider extends AbstractDependencyProvider
{
    public const PLUGINS_PRODUCT_CONFIGURATOR_REQUEST = 'PLUGINS_PRODUCT_CONFIGURATOR_REQUEST';
    public const PLUGIN_PRODUCT_CONFIGURATOR_REQUEST_DEFAULT = 'PLUGIN_PRODUCT_CONFIGURATOR_REQUEST_DEFAULT';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addProductConfiguratorRequestPlugins($container);
        $container = $this->addProductConfiguratorRequestDefaultPlugin($container);


        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductConfiguratorRequestPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_CONFIGURATOR_REQUEST, function (): array {
            return $this->getProductConfiguratorRequestPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfigurationExtensionRequestPluginInterface[]
     */
    protected function getProductConfiguratorRequestPlugins(): array
    {
        return [];
    }


    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     *
     * @throws \Spryker\Service\Container\Exception\FrozenServiceException
     */
    protected function addProductConfiguratorRequestDefaultPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_PRODUCT_CONFIGURATOR_REQUEST_DEFAULT, function (): ProductConfigurationExtensionRequestPluginInterface {
            return $this->getProductConfiguratorRequestDefaultPlugin();
        });

        return $container;

    }

    /**
     * @throws \Spryker\Client\ProductConfiguration\Exception\MissingProductConfigurationRequestDefaultPluginException
     *
     * @return \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfigurationExtensionRequestPluginInterface
     */
    protected function getProductConfiguratorRequestDefaultPlugin(): ProductConfigurationExtensionRequestPluginInterface
    {
        throw new MissingProductConfigurationRequestDefaultPluginException(
            sprintf(
                "Missing instance of %s! You need to provide default product configurator request plugin
                      in your own ProductConfiguration::getProductConfiguratorRequestDefaultPlugin().",
                ProductConfigurationExtensionRequestPluginInterface::class
            )
        );
    }
}
