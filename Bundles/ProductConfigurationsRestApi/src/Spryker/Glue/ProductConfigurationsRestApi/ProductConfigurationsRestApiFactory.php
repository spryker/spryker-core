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
            $this->getCartItemProductConfigurationMapperPlugins(),
            $this->getRestCartItemProductConfigurationMapperPlugins()
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
     * @return \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface
     */
    public function getProductConfigurationStorageClient(): ProductConfigurationsRestApiToProductConfigurationStorageClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationsRestApiDependencyProvider::CLIENT_PRODUCT_CONFIGURATION_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\CartItemProductConfigurationMapperPluginInterface[]
     */
    public function getCartItemProductConfigurationMapperPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationsRestApiDependencyProvider::PLUGINS_CART_ITEM_PRODUCT_CONFIGURATION_MAPPER);
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\RestCartItemProductConfigurationMapperPluginInterface[]
     */
    public function getRestCartItemProductConfigurationMapperPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationsRestApiDependencyProvider::PLUGINS_REST_CART_ITEM_PRODUCT_CONFIGURATION_MAPPER);
    }
}
