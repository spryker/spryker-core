<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientBridge;

/**
 * @method \Spryker\Glue\ProductConfigurationsRestApi\ProductConfigurationsRestApiConfig getConfig()
 */
class ProductConfigurationsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_CONFIGURATION_STORAGE = 'CLIENT_PRODUCT_CONFIGURATION_STORAGE';

    public const PLUGINS_CART_ITEM_PRODUCT_CONFIGURATION_MAPPER = 'PLUGINS_CART_ITEM_PRODUCT_CONFIGURATION_MAPPER';
    public const PLUGINS_REST_CART_ITEM_PRODUCT_CONFIGURATION_MAPPER = 'PLUGINS_REST_CART_ITEM_PRODUCT_CONFIGURATION_MAPPER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addProductConfigurationStorageClient($container);
        $container = $this->addCartItemProductConfigurationMapperPlugins($container);
        $container = $this->addRestCartItemProductConfigurationMapperPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductConfigurationStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_CONFIGURATION_STORAGE, function (Container $container) {
            return new ProductConfigurationsRestApiToProductConfigurationStorageClientBridge(
                $container->getLocator()->productConfigurationStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCartItemProductConfigurationMapperPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CART_ITEM_PRODUCT_CONFIGURATION_MAPPER, function () {
            return $this->getCartItemProductConfigurationMapperPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRestCartItemProductConfigurationMapperPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REST_CART_ITEM_PRODUCT_CONFIGURATION_MAPPER, function () {
            return $this->getRestCartItemProductConfigurationMapperPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\CartItemProductConfigurationMapperPluginInterface[]
     */
    protected function getCartItemProductConfigurationMapperPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\RestCartItemProductConfigurationMapperPluginInterface[]
     */
    protected function getRestCartItemProductConfigurationMapperPlugins(): array
    {
        return [];
    }
}
