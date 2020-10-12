<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration;

use GuzzleHttp\Client as GuzzleHttpClient;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCurrencyClientBridge;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCustomerClientBridge;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToLocaleBridge;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToPriceClientBridge;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToStoreClientBridge;
use Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToHttpClientAdapter;
use Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToProductConfigurationDataChecksumGeneratorBridge;
use Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingBridge;
use Spryker\Client\ProductConfiguration\Exception\MissingDefaultProductConfigurationRequestPluginException;
use Spryker\Client\ProductConfiguration\Exception\MissingDefaultProductConfiguratorResponsePluginException;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface;
use SprykerSdk\ProductConfigurationSdk\Service\ProductConfigurationDataChecksumGenerator;
use SprykerSdk\ProductConfigurationSdk\Service\ProductConfigurationDataChecksumGeneratorInterface;

/**
 * @method \Spryker\Client\ProductConfiguration\ProductConfigurationConfig getConfig()
 */
class ProductConfigurationDependencyProvider extends AbstractDependencyProvider
{
    public const PLUGINS_PRODUCT_CONFIGURATOR_REQUEST = 'PLUGINS_PRODUCT_CONFIGURATOR_REQUEST';
    public const PLUGIN_DEFAULT_PRODUCT_CONFIGURATOR_REQUEST = 'PLUGIN_DEFAULT_PRODUCT_CONFIGURATOR_REQUEST';

    public const PLUGINS_PRODUCT_CONFIGURATOR_RESPONSE = 'PLUGINS_PRODUCT_CONFIGURATOR_RESPONSE';
    public const PLUGIN_DEFAULT_PRODUCT_CONFIGURATOR_RESPONSE = 'PLUGIN_DEFAULT_PRODUCT_CONFIGURATOR_RESPONSE';

    public const PLUGINS_PRODUCT_CONFIGURATOR_REQUEST_EXPANDER = 'PLUGINS_PRODUCT_CONFIGURATOR_REQUEST_EXPANDER';

    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const CLIENT_STORE = 'CLIENT_STORE';
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';
    public const CLIENT_PRICE = 'CLIENT_PRICE';
    public const CLIENT_CURRENCY = 'CLIENT_CURRENCY';
    public const CLIENT_HTTP = 'CLIENT_HTTP';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const SERVICE_PRODUCT_CONFIGURATION_DATA_CHECKSUM_GENERATOR = 'SERVICE_PRODUCT_CONFIGURATION_DATA_CHECKSUM_GENERATOR';

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
        $container = $this->addCustomerClient($container);
        $container = $this->addStoreClient($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addPriceClient($container);
        $container = $this->addCurrencyClient($container);
        $container = $this->addProductConfigurationRequestExpanderPlugins($container);
        $container = $this->addGuzzleClient($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addProductConfigurationDataChecksumGenerator($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addGuzzleClient(Container $container): Container
    {
        $container->set(static::CLIENT_HTTP, function () {
            return new ProductConfigurationToHttpClientAdapter(
                new GuzzleHttpClient()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCurrencyClient(Container $container): Container
    {
        $container->set(static::CLIENT_CURRENCY, function (Container $container) {
            return new ProductConfigurationToCurrencyClientBridge(
                $container->getLocator()->currency()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRICE, function (Container $container) {
            return new ProductConfigurationToPriceClientBridge(
                $container->getLocator()->price()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container) {
            return new ProductConfigurationToLocaleBridge(
                $container->getLocator()->locale()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new ProductConfigurationToCustomerClientBridge(
                $container->getLocator()->customer()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new ProductConfigurationToStoreClientBridge(
                $container->getLocator()->store()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ProductConfigurationToUtilEncodingBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

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
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductConfigurationRequestExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_CONFIGURATOR_REQUEST_EXPANDER, function () {
            return $this->getProductConfigurationRequestExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductConfigurationDataChecksumGenerator(Container $container): Container
    {
        $container->set(static::SERVICE_PRODUCT_CONFIGURATION_DATA_CHECKSUM_GENERATOR, function (Container $container) {
            return new ProductConfigurationToProductConfigurationDataChecksumGeneratorBridge(
                $this->getProductConfigurationDataChecksumGenerator()
            );
        });

        return $container;
    }

    /**
     * @return \SprykerSdk\ProductConfigurationSdk\Service\ProductConfigurationDataChecksumGeneratorInterface
     */
    protected function getProductConfigurationDataChecksumGenerator(): ProductConfigurationDataChecksumGeneratorInterface
    {
        return new ProductConfigurationDataChecksumGenerator();
    }

    /**
     * @return \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestExpanderInterface[]
     */
    protected function getProductConfigurationRequestExpanderPlugins(): array
    {
        return [];
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
