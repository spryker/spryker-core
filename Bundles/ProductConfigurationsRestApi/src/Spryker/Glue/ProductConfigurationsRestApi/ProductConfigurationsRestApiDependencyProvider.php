<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientBridge;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Service\ProductConfigurationsRestApiToProductConfigurationServiceBridge;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Service\ProductConfigurationsRestApiToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Glue\ProductConfigurationsRestApi\ProductDiscontinuedRestApiConfig getConfig()
 */
class ProductConfigurationsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_CONFIGURATION_STORAGE = 'CLIENT_PRODUCT_CONFIGURATION_STORAGE';

    public const SERVICE_PRODUCT_CONFIGURATION = 'SERVICE_PRODUCT_CONFIGURATION';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    public const PLUGINS_PRODUCT_CONFIGURATION_MAPPER = 'PLUGINS_PRODUCT_CONFIGURATION_MAPPER';
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
        $container = $this->addProductConfigurationService($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addProductConfigurationMapperPlugins($container);
        $container = $this->addRestCartItemProductConfigurationMapperPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function addProductConfigurationStorageClient(Container $container): Container
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
    protected function addProductConfigurationService(Container $container): Container
    {
        $container->set(static::SERVICE_PRODUCT_CONFIGURATION, function (Container $container) {
            return new ProductConfigurationsRestApiToProductConfigurationServiceBridge(
                $container->getLocator()->productConfiguration()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ProductConfigurationsRestApiToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function addProductConfigurationMapperPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_CONFIGURATION_MAPPER, function () {
            return $this->getProductConfigurationMapperPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function addRestCartItemProductConfigurationMapperPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REST_CART_ITEM_PRODUCT_CONFIGURATION_MAPPER, function () {
            return $this->getRestCartItemProductConfigurationMapperPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\ProductConfigurationMapperPluginInterface[]
     */
    public function getProductConfigurationMapperPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\RestCartItemProductConfigurationMapperPluginInterface[]
     */
    public function getRestCartItemProductConfigurationMapperPlugins(): array
    {
        return [];
    }
}
