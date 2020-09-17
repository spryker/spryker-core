<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfigurationGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\SalesProductConfigurationGui\SalesProductConfigurationGuiConfig getConfig()
 */
class SalesProductConfigurationGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_PRODUCT_CONFIGURATION_RENDER_STRATEGY = 'PLUGINS_PRODUCT_CONFIGURATION_RENDER_STRATEGY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addProductConfigurationRenderStrategyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConfigurationRenderStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_CONFIGURATION_RENDER_STRATEGY, function () {
            return $this->getProductConfigurationRenderStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\SalesProductConfigurationGuiExtension\Dependency\Plugin\ProductConfigurationRenderPluginInterface[]
     */
    protected function getProductConfigurationRenderStrategyPlugins(): array
    {
        return [];
    }
}
