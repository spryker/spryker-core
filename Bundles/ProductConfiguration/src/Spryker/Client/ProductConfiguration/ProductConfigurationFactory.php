<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductConfiguration\Checker\QuoteProductConfigurationChecker;
use Spryker\Client\ProductConfiguration\Checker\QuoteProductConfigurationCheckerInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCurrencyClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToLocaleInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToStoreClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToPriceClientInterface;
use Spryker\Client\ProductConfiguration\Http\ProductConfigurationGuzzleHttpClient;
use Spryker\Client\ProductConfiguration\Http\ProductConfigurationGuzzleHttpClientInterface;
use Spryker\Client\ProductConfiguration\Processor\ProductConfiguratorResponseProcessor;
use Spryker\Client\ProductConfiguration\Processor\ProductConfiguratorResponseProcessorInterface;
use Spryker\Client\ProductConfiguration\Resolver\ProductConfiguratorAccessTokenRedirectResolver;
use Spryker\Client\ProductConfiguration\Resolver\ProductConfiguratorAccessTokenRedirectResolverInterface;
use Spryker\Client\ProductConfiguration\Resolver\ProductConfiguratorRedirectResolver;
use Spryker\Client\ProductConfiguration\Resolver\ProductConfiguratorRedirectResolverInterface;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestExpanderInterface;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCustomerClientInterface;
use GuzzleHttp\ClientInterface;

class ProductConfigurationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductConfiguration\Resolver\ProductConfiguratorRedirectResolverInterface
     */
    public function createProductConfiguratorRedirectResolver(): ProductConfiguratorRedirectResolverInterface
    {
        return new ProductConfiguratorRedirectResolver(
            $this->getProductConfiguratorRequestPlugins(),
            $this->getDefaultProductConfiguratorRequestPlugin(),
            $this->getCustomerClient(),
            $this->getStoreClient(),
            $this->getLocaleClient(),
            $this->getPriceClient(),
            $this->getCurrencyClient()
        );
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Resolver\ProductConfiguratorAccessTokenRedirectResolverInterface
     */
    public function createProductConfigurationAccessTokenRedirectResolver(): ProductConfiguratorAccessTokenRedirectResolverInterface
    {
        return new ProductConfiguratorAccessTokenRedirectResolver(
           $this->getProductConfiguratorRequestExpanderPlugin(),
            $this->createProductConfigurationGuzzleHttpClient()
        );
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Processor\ProductConfiguratorResponseProcessorInterface
     */
    public function createProductConfiguratorResponseProcessor(): ProductConfiguratorResponseProcessorInterface
    {
        return new ProductConfiguratorResponseProcessor(
            $this->getProductConfiguratorResponsePlugins(),
            $this->getDefaultProductConfiguratorResponsePlugin()
        );
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Checker\QuoteProductConfigurationCheckerInterface
     */
    public function createQuoteProductConfigurationChecker(): QuoteProductConfigurationCheckerInterface
    {
        return new QuoteProductConfigurationChecker();
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Http\ProductConfigurationGuzzleHttpClientInterface
     */
    public function createProductConfigurationGuzzleHttpClient(): ProductConfigurationGuzzleHttpClientInterface
    {
        return new ProductConfigurationGuzzleHttpClient($this->getGuzzleClient());
    }

    /**
     * @return \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface[]
     */
    public function getProductConfiguratorRequestPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::PLUGINS_PRODUCT_CONFIGURATOR_REQUEST);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface
     */
    public function getDefaultProductConfiguratorRequestPlugin(): ProductConfiguratorRequestPluginInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::PLUGIN_DEFAULT_PRODUCT_CONFIGURATOR_REQUEST);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface[]
     */
    public function getProductConfiguratorResponsePlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::PLUGINS_PRODUCT_CONFIGURATOR_RESPONSE);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface
     */
    public function getDefaultProductConfiguratorResponsePlugin(): ProductConfiguratorResponsePluginInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::PLUGIN_DEFAULT_PRODUCT_CONFIGURATOR_RESPONSE);
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCustomerClientInterface
     */
    public function getCustomerClient(): ProductConfigurationToCustomerClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToStoreClientInterface
     */
    public function getStoreClient(): ProductConfigurationToStoreClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToLocaleInterface
     */
    public function getLocaleClient(): ProductConfigurationToLocaleInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToPriceClientInterface
     */
    public function getPriceClient(): ProductConfigurationToPriceClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::CLIENT_PRICE);
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCurrencyClientInterface
     */
    public function getCurrencyClient(): ProductConfigurationToCurrencyClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::CLIENT_CURRENCY);
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    public function getGuzzleClient(): ClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::CLIENT_GUZZLE);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestExpanderInterface[]
     */
    public function getProductConfiguratorRequestExpanderPlugin(): array
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::PLUGIN_PRODUCT_CONFIGURATOR_REQUEST_EXPANDER);
    }
}
