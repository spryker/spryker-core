<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationShoppingListsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\ProductConfigurationShoppingListsRestApi\ProductConfigurationShoppingListsRestApiConfig getConfig()
 */
class ProductConfigurationShoppingListsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_CONFIGURATION_PRICE_MAPPER = 'PLUGINS_PRODUCT_CONFIGURATION_PRICE_MAPPER';

    /**
     * @var string
     */
    public const PLUGINS_REST_PRODUCT_CONFIGURATION_PRICE_MAPPER = 'PLUGINS_REST_PRODUCT_CONFIGURATION_PRICE_MAPPER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addProductConfigurationMapperPlugins($container);
        $container = $this->addRestProductConfigurationPriceMapperPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductConfigurationMapperPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_CONFIGURATION_PRICE_MAPPER, function () {
            return $this->getProductConfigurationPriceMapperPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRestProductConfigurationPriceMapperPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REST_PRODUCT_CONFIGURATION_PRICE_MAPPER, function () {
            return $this->getRestProductConfigurationPriceMapperPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\ProductConfigurationShoppingListsRestApiExtension\Dependency\Plugin\ProductConfigurationPriceMapperPluginInterface>
     */
    protected function getProductConfigurationPriceMapperPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Glue\ProductConfigurationShoppingListsRestApiExtension\Dependency\Plugin\RestProductConfigurationPriceMapperPluginInterface>
     */
    protected function getRestProductConfigurationPriceMapperPlugins(): array
    {
        return [];
    }
}
