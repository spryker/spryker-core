<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationWishlistsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductConfigurationWishlistsRestApi\Dependency\Service\ProductConfigurationWishlistsRestApiToProductConfigurationServiceBridge;

/**
 * @method \Spryker\Glue\ProductConfigurationWishlistsRestApi\ProductConfigurationWishlistsRestApiConfig getConfig()
 */
class ProductConfigurationWishlistsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PRODUCT_CONFIGURATION_STORAGE = 'CLIENT_PRODUCT_CONFIGURATION_STORAGE';

    /**
     * @var string
     */
    public const SERVICE_PRODUCT_CONFIGURATION = 'SERVICE_PRODUCT_CONFIGURATION';

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

        $container = $this->addProductConfigurationService($container);
        $container = $this->addProductConfigurationMapperPlugins($container);
        $container = $this->addRestProductConfigurationPriceMapperPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductConfigurationService(Container $container): Container
    {
        $container->set(static::SERVICE_PRODUCT_CONFIGURATION, function (Container $container) {
            return new ProductConfigurationWishlistsRestApiToProductConfigurationServiceBridge(
                $container->getLocator()->productConfiguration()->service(),
            );
        });

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
     * @return array<\Spryker\Glue\ProductConfigurationWishlistsRestApiExtension\Dependency\Plugin\ProductConfigurationPriceMapperPluginInterface>
     */
    protected function getProductConfigurationPriceMapperPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Glue\ProductConfigurationWishlistsRestApiExtension\Dependency\Plugin\RestProductConfigurationPriceMapperPluginInterface>
     */
    protected function getRestProductConfigurationPriceMapperPlugins(): array
    {
        return [];
    }
}
