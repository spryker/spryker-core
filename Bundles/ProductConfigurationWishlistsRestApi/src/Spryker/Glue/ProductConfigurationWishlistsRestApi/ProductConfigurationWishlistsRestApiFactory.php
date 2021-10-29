<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationWishlistsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductConfigurationWishlistsRestApi\Dependency\Service\ProductConfigurationWishlistsRestApiToProductConfigurationServiceInterface;
use Spryker\Glue\ProductConfigurationWishlistsRestApi\Processor\Mapper\ProductConfigurationInstanceMapper;
use Spryker\Glue\ProductConfigurationWishlistsRestApi\Processor\Mapper\ProductConfigurationInstanceMapperInterface;
use Spryker\Glue\ProductConfigurationWishlistsRestApi\Processor\Mapper\ProductConfigurationInstancePriceMapper;
use Spryker\Glue\ProductConfigurationWishlistsRestApi\Processor\Mapper\ProductConfigurationInstancePriceMapperInterface;
use Spryker\Glue\ProductConfigurationWishlistsRestApi\Processor\Mapper\ProductConfigurationRestWishlistItemsAttributesMapper;
use Spryker\Glue\ProductConfigurationWishlistsRestApi\Processor\Mapper\ProductConfigurationRestWishlistItemsAttributesMapperInterface;

class ProductConfigurationWishlistsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductConfigurationWishlistsRestApi\Processor\Mapper\ProductConfigurationRestWishlistItemsAttributesMapperInterface
     */
    public function createProductConfigurationRestWishlistItemsAttributesMapper(): ProductConfigurationRestWishlistItemsAttributesMapperInterface
    {
        return new ProductConfigurationRestWishlistItemsAttributesMapper(
            $this->createProductConfigurationInstanceMapper(),
            $this->getProductConfigurationService(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationWishlistsRestApi\Processor\Mapper\ProductConfigurationInstanceMapperInterface
     */
    public function createProductConfigurationInstanceMapper(): ProductConfigurationInstanceMapperInterface
    {
        return new ProductConfigurationInstanceMapper(
            $this->createProductConfigurationInstancePriceMapper(),
            $this->getProductConfigurationPriceMapperPlugins(),
            $this->getRestProductConfigurationPriceMapperPlugins(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationWishlistsRestApi\Processor\Mapper\ProductConfigurationInstancePriceMapperInterface
     */
    public function createProductConfigurationInstancePriceMapper(): ProductConfigurationInstancePriceMapperInterface
    {
        return new ProductConfigurationInstancePriceMapper();
    }

    /**
     * @return array<\Spryker\Glue\ProductConfigurationWishlistsRestApiExtension\Dependency\Plugin\RestProductConfigurationPriceMapperPluginInterface>
     */
    public function getRestProductConfigurationPriceMapperPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationWishlistsRestApiDependencyProvider::PLUGINS_REST_PRODUCT_CONFIGURATION_PRICE_MAPPER);
    }

    /**
     * @return array<\Spryker\Glue\ProductConfigurationWishlistsRestApiExtension\Dependency\Plugin\ProductConfigurationPriceMapperPluginInterface>
     */
    public function getProductConfigurationPriceMapperPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationWishlistsRestApiDependencyProvider::PLUGINS_PRODUCT_CONFIGURATION_PRICE_MAPPER);
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationWishlistsRestApi\Dependency\Service\ProductConfigurationWishlistsRestApiToProductConfigurationServiceInterface
     */
    public function getProductConfigurationService(): ProductConfigurationWishlistsRestApiToProductConfigurationServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationWishlistsRestApiDependencyProvider::SERVICE_PRODUCT_CONFIGURATION);
    }
}
