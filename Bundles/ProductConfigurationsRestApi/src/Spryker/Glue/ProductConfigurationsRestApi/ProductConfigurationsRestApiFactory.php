<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Service\ProductConfigurationsRestApiToProductConfigurationServiceInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Service\ProductConfigurationsRestApiToUtilEncodingServiceInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Expander\ProductConfigurationCartItemExpander;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Expander\ProductConfigurationCartItemExpanderInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Expander\ProductConfigurationProductConcreteExpander;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Expander\ProductConfigurationProductConcreteExpanderInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationInstanceMapper;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationInstanceMapperInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationInstancePriceMapper;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationInstancePriceMapperInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationPriceProductVolumeMapper;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationPriceProductVolumeMapperInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationRestOrderAttributesMapper;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationRestOrderAttributesMapperInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\RestCartItemProductConfigurationPriceProductVolumeMapper;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\RestCartItemProductConfigurationPriceProductVolumeMapperInterface;

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
     * @return \Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\RestCartItemProductConfigurationPriceProductVolumeMapperInterface
     */
    public function createRestCartItemProductConfigurationPriceProductVolumeMapper(): RestCartItemProductConfigurationPriceProductVolumeMapperInterface
    {
        return new RestCartItemProductConfigurationPriceProductVolumeMapper(
            $this->getProductConfigurationService(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationPriceProductVolumeMapperInterface
     */
    public function createProductConfigurationPriceProductVolumeMapper(): ProductConfigurationPriceProductVolumeMapperInterface
    {
        return new ProductConfigurationPriceProductVolumeMapper();
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
     * @return \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Service\ProductConfigurationsRestApiToProductConfigurationServiceInterface
     */
    public function getProductConfigurationService(): ProductConfigurationsRestApiToProductConfigurationServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationsRestApiDependencyProvider::SERVICE_PRODUCT_CONFIGURATION);
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Service\ProductConfigurationsRestApiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductConfigurationsRestApiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationsRestApiDependencyProvider::SERVICE_UTIL_ENCODING);
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
