<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCurrencyClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCustomerClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToLocaleInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToPriceClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToPriceProductVolumeClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToStoreClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToChecksumGeneratorInterface;
use Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToHttpClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToPriceProductServiceInterface;
use Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface;
use Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestDataCurrencyExpander;
use Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestDataCustomerExpander;
use Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestDataExpanderComposite;
use Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestDataExpanderInterface;
use Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestDataLocaleExpander;
use Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestDataPriceExpander;
use Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestDataStoreExpander;
use Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestExpander;
use Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestExpanderInterface;
use Spryker\Client\ProductConfiguration\Mapper\ProductConfigurationInstancePriceMapper;
use Spryker\Client\ProductConfiguration\Mapper\ProductConfigurationInstancePriceMapperInterface;
use Spryker\Client\ProductConfiguration\Mapper\ProductConfigurationResponseMapper;
use Spryker\Client\ProductConfiguration\Mapper\ProductConfigurationResponseMapperInterface;
use Spryker\Client\ProductConfiguration\Sender\ProductConfiguratorRequestSender;
use Spryker\Client\ProductConfiguration\Sender\ProductConfiguratorRequestSenderInterface;
use Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorCheckSumResponseValidator;
use Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorCheckSumResponseValidatorComposite;
use Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorMandatoryFieldsResponseValidator;
use Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorResponseValidatorInterface;
use Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorTimestampResponseValidator;
use Spryker\Service\ProductConfiguration\ProductConfigurationServiceInterface;

/**
 * @method \Spryker\Client\ProductConfiguration\ProductConfigurationConfig getConfig()
 */
class ProductConfigurationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestExpanderInterface
     */
    public function createProductConfiguratorRequestExpander(): ProductConfiguratorRequestExpanderInterface
    {
        return new ProductConfiguratorRequestExpander(
            $this->createProductConfiguratorRequestDataExpanderComposite(),
            $this->getProductConfiguratorRequestExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestDataExpanderInterface
     */
    public function createProductConfiguratorRequestDataExpanderComposite(): ProductConfiguratorRequestDataExpanderInterface
    {
        return new ProductConfiguratorRequestDataExpanderComposite(
            [
                $this->createProductConfiguratorRequestDataCustomerExpander(),
                $this->createProductConfiguratorRequestDataStoreExpander(),
                $this->createProductConfiguratorRequestDataLocaleExpander(),
                $this->createProductConfiguratorRequestDataCurrencyExpander(),
                $this->createProductConfiguratorRequestDataPriceExpander(),
            ]
        );
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestDataExpanderInterface
     */
    public function createProductConfiguratorRequestDataCurrencyExpander(): ProductConfiguratorRequestDataExpanderInterface
    {
        return new ProductConfiguratorRequestDataCurrencyExpander($this->getCurrencyClient());
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestDataExpanderInterface
     */
    public function createProductConfiguratorRequestDataCustomerExpander(): ProductConfiguratorRequestDataExpanderInterface
    {
        return new ProductConfiguratorRequestDataCustomerExpander($this->getCustomerClient());
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestDataExpanderInterface
     */
    public function createProductConfiguratorRequestDataStoreExpander(): ProductConfiguratorRequestDataExpanderInterface
    {
        return new ProductConfiguratorRequestDataStoreExpander($this->getStoreClient());
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestDataExpanderInterface
     */
    public function createProductConfiguratorRequestDataLocaleExpander(): ProductConfiguratorRequestDataExpanderInterface
    {
        return new ProductConfiguratorRequestDataLocaleExpander($this->getLocaleClient());
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestDataExpanderInterface
     */
    public function createProductConfiguratorRequestDataPriceExpander(): ProductConfiguratorRequestDataExpanderInterface
    {
        return new ProductConfiguratorRequestDataPriceExpander($this->getPriceClient());
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Mapper\ProductConfigurationInstancePriceMapperInterface
     */
    public function createProductConfigurationInstancePriceMapper(): ProductConfigurationInstancePriceMapperInterface
    {
        return new ProductConfigurationInstancePriceMapper(
            $this->getPriceProductService(),
            $this->getProductConfigurationService(),
            $this->getPriceProductConfigurationPriceExtractorPlugins()
        );
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
            $this->getChecksumGenerator()
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
     * @return \Spryker\Client\ProductConfiguration\Sender\ProductConfiguratorRequestSenderInterface
     */
    public function createProductConfiguratorRequestSender(): ProductConfiguratorRequestSenderInterface
    {
        return new ProductConfiguratorRequestSender(
            $this->getHttpClient(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToHttpClientInterface
     */
    public function getHttpClient(): ProductConfigurationToHttpClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::CLIENT_HTTP);
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToChecksumGeneratorInterface
     */
    public function getChecksumGenerator(): ProductConfigurationToChecksumGeneratorInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::CHECKSUM_GENERATOR);
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
     * @return \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToPriceProductVolumeClientInterface
     */
    public function getPriceProductVolumeClient(): ProductConfigurationToPriceProductVolumeClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::CLIENT_PRICE_PRODUCT_VOLUME);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestExpanderPluginInterface[]
     */
    public function getProductConfiguratorRequestExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::PLUGINS_PRODUCT_CONFIGURATOR_REQUEST_EXPANDER);
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductConfigurationToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToPriceProductServiceInterface
     */
    public function getPriceProductService(): ProductConfigurationToPriceProductServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::SERVICE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Service\ProductConfiguration\ProductConfigurationServiceInterface
     */
    public function getProductConfigurationService(): ProductConfigurationServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::SERVICE_PRODUCT_CONFIGURATION);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfigurationPriceExtractorPluginInterface[]
     */
    public function getPriceProductConfigurationPriceExtractorPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::PLUGINS_PRODUCT_CONFIGURATION_PRICE_EXTRACTOR);
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Mapper\ProductConfigurationResponseMapperInterface
     */
    public function createProductConfigurationResponseMapper(): ProductConfigurationResponseMapperInterface
    {
        return new ProductConfigurationResponseMapper(
            $this->createProductConfigurationInstancePriceMapper()
        );
    }
}
