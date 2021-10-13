<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Expander\ProductConfigurationCartItemExpander;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Expander\ProductConfigurationCartItemExpanderInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Expander\ProductConfigurationProductConcreteExpander;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Expander\ProductConfigurationProductConcreteExpanderInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationInstanceMapper;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationInstanceMapperInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationInstancePriceMapper;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationInstancePriceMapperInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationRestOrderAttributesMapper;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationRestOrderAttributesMapperInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Validator\CartItemProductConfigurationRestRequestValidator;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Validator\CartItemProductConfigurationRestRequestValidatorInterface;

class ProductConfigurationsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApi\Processor\Expander\ProductConfigurationProductConcreteExpanderInterface
     */
    public function createProductConfigurationProductConcreteExpander(): ProductConfigurationProductConcreteExpanderInterface
    {
        return new ProductConfigurationProductConcreteExpander($this->getProductConfigurationStorageClient());
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationRestOrderAttributesMapperInterface
     */
    public function createProductConfigurationRestOrderAttributesMapper(): ProductConfigurationRestOrderAttributesMapperInterface
    {
        return new ProductConfigurationRestOrderAttributesMapper();
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApi\Processor\Expander\ProductConfigurationCartItemExpanderInterface
     */
    public function createProductConfigurationCartItemExpander(): ProductConfigurationCartItemExpanderInterface
    {
        return new ProductConfigurationCartItemExpander(
            $this->createProductConfigurationInstanceMapper(),
            $this->getProductConfigurationStorageClient()
        );
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationInstanceMapperInterface
     */
    public function createProductConfigurationInstanceMapper(): ProductConfigurationInstanceMapperInterface
    {
        return new ProductConfigurationInstanceMapper(
            $this->createProductConfigurationInstancePriceMapper(),
            $this->getProductConfigurationPriceMapperPlugins(),
            $this->getRestProductConfigurationPriceMapperPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationInstancePriceMapperInterface
     */
    public function createProductConfigurationInstancePriceMapper(): ProductConfigurationInstancePriceMapperInterface
    {
        return new ProductConfigurationInstancePriceMapper();
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApi\Processor\Validator\CartItemProductConfigurationRestRequestValidatorInterface
     */
    public function createCartItemProductConfigurationRestRequestValidator(): CartItemProductConfigurationRestRequestValidatorInterface
    {
        return new CartItemProductConfigurationRestRequestValidator(
            $this->getProductConfigurationStorageClient()
        );
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface
     */
    public function getProductConfigurationStorageClient(): ProductConfigurationsRestApiToProductConfigurationStorageClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationsRestApiDependencyProvider::CLIENT_PRODUCT_CONFIGURATION_STORAGE);
    }

    /**
     * @return array<\Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\ProductConfigurationPriceMapperPluginInterface>
     */
    public function getProductConfigurationPriceMapperPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationsRestApiDependencyProvider::PLUGINS_PRODUCT_CONFIGURATION_PRICE_MAPPER);
    }

    /**
     * @return array<\Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\RestProductConfigurationPriceMapperPluginInterface>
     */
    public function getRestProductConfigurationPriceMapperPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationsRestApiDependencyProvider::PLUGINS_REST_PRODUCT_CONFIGURATION_PRICE_MAPPER);
    }
}
