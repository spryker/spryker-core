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
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCustomerClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToLocaleInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToPriceClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToStoreClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToHttpClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToProductConfigurationDataChecksumGeneratorInterface;
use Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingInterface;
use Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestDataExpander;
use Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestDataExpanderInterface;
use Spryker\Client\ProductConfiguration\Processor\ProductConfiguratorResponseProcessor;
use Spryker\Client\ProductConfiguration\Processor\ProductConfiguratorResponseProcessorInterface;
use Spryker\Client\ProductConfiguration\Resolver\ProductConfiguratorAccessTokenRedirectResolver;
use Spryker\Client\ProductConfiguration\Resolver\ProductConfiguratorAccessTokenRedirectResolverInterface;
use Spryker\Client\ProductConfiguration\Resolver\ProductConfiguratorRedirectResolver;
use Spryker\Client\ProductConfiguration\Resolver\ProductConfiguratorRedirectResolverInterface;
use Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorCheckSumResponseValidator;
use Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorCheckSumResponseValidatorComposite;
use Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorMandatoryFieldsResponseValidator;
use Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorResponseValidatorInterface;
use Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorTimestampResponseValidator;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface;

/**
 * @method \Spryker\Client\ProductConfiguration\ProductConfigurationConfig getConfig()
 */
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
            $this->createProductConfiguratorRequestDataExpander()
        );
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestDataExpanderInterface
     */
    public function createProductConfiguratorRequestDataExpander(): ProductConfiguratorRequestDataExpanderInterface
    {
        return new ProductConfiguratorRequestDataExpander(
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
    public function createProductConfiguratorAccessTokenRedirectResolver(): ProductConfiguratorAccessTokenRedirectResolverInterface
    {
        return new ProductConfiguratorAccessTokenRedirectResolver(
            $this->getHttpClient(),
            $this->getUtilEncodingService(),
            $this->getProductConfiguratorRequestExpanderPlugins()
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
     * @return \Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorResponseValidatorInterface
     */
    public function createProductConfiguratorCheckSumResponseValidatorComposite(): ProductConfiguratorResponseValidatorInterface
    {
        return new ProductConfiguratorCheckSumResponseValidatorComposite(
            $this->createProductConfiguratorCheckSumResponseValidators()
        );
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorResponseValidatorInterface[]
     */
    public function createProductConfiguratorCheckSumResponseValidators(): array
    {
        return [
            $this->createProductConfiguratorMandatoryFieldsResponseValidator(),
            $this->createProductConfiguratorTimestampResponseValidator(),
            $this->createProductConfiguratorCheckSumResponseValidator(),
        ];
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorResponseValidatorInterface
     */
    public function createProductConfiguratorCheckSumResponseValidator(): ProductConfiguratorResponseValidatorInterface
    {
        return new ProductConfiguratorCheckSumResponseValidator(
            $this->getConfig(),
            $this->getProductConfigurationDataChecksumGenerator()
        );
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorResponseValidatorInterface
     */
    public function createProductConfiguratorMandatoryFieldsResponseValidator(): ProductConfiguratorResponseValidatorInterface
    {
        return new ProductConfiguratorMandatoryFieldsResponseValidator();
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorResponseValidatorInterface
     */
    public function createProductConfiguratorTimestampResponseValidator(): ProductConfiguratorResponseValidatorInterface
    {
        return new ProductConfiguratorTimestampResponseValidator($this->getConfig());
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToHttpClientInterface
     */
    public function getHttpClient(): ProductConfigurationToHttpClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::CLIENT_HTTP);
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToProductConfigurationDataChecksumGeneratorInterface
     */
    public function getProductConfigurationDataChecksumGenerator(): ProductConfigurationToProductConfigurationDataChecksumGeneratorInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::SERVICE_PRODUCT_CONFIGURATION_DATA_CHECKSUM_GENERATOR);
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
     * @return \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestExpanderInterface[]
     */
    public function getProductConfiguratorRequestExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::PLUGINS_PRODUCT_CONFIGURATOR_REQUEST_EXPANDER);
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingInterface
     */
    public function getUtilEncodingService(): ProductConfigurationToUtilEncodingInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
