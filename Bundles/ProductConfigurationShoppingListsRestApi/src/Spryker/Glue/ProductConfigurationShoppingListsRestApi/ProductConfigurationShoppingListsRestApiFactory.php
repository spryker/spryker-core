<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationShoppingListsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductConfigurationShoppingListsRestApi\Processor\Mapper\ProductConfigurationInstanceMapper;
use Spryker\Glue\ProductConfigurationShoppingListsRestApi\Processor\Mapper\ProductConfigurationInstanceMapperInterface;
use Spryker\Glue\ProductConfigurationShoppingListsRestApi\Processor\Mapper\ProductConfigurationInstancePriceMapper;
use Spryker\Glue\ProductConfigurationShoppingListsRestApi\Processor\Mapper\ProductConfigurationInstancePriceMapperInterface;
use Spryker\Glue\ProductConfigurationShoppingListsRestApi\Processor\Mapper\ProductConfigurationRestShoppingListItemsAttributesMapper;
use Spryker\Glue\ProductConfigurationShoppingListsRestApi\Processor\Mapper\ProductConfigurationRestShoppingListItemsAttributesMapperInterface;

class ProductConfigurationShoppingListsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductConfigurationShoppingListsRestApi\Processor\Mapper\ProductConfigurationRestShoppingListItemsAttributesMapperInterface
     */
    public function createProductConfigurationRestShoppingListItemsAttributesMapper(): ProductConfigurationRestShoppingListItemsAttributesMapperInterface
    {
        return new ProductConfigurationRestShoppingListItemsAttributesMapper(
            $this->createProductConfigurationInstanceMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationShoppingListsRestApi\Processor\Mapper\ProductConfigurationInstanceMapperInterface
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
     * @return \Spryker\Glue\ProductConfigurationShoppingListsRestApi\Processor\Mapper\ProductConfigurationInstancePriceMapperInterface
     */
    public function createProductConfigurationInstancePriceMapper(): ProductConfigurationInstancePriceMapperInterface
    {
        return new ProductConfigurationInstancePriceMapper();
    }

    /**
     * @return array<\Spryker\Glue\ProductConfigurationShoppingListsRestApiExtension\Dependency\Plugin\RestProductConfigurationPriceMapperPluginInterface>
     */
    public function getRestProductConfigurationPriceMapperPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationShoppingListsRestApiDependencyProvider::PLUGINS_REST_PRODUCT_CONFIGURATION_PRICE_MAPPER);
    }

    /**
     * @return array<\Spryker\Glue\ProductConfigurationShoppingListsRestApiExtension\Dependency\Plugin\ProductConfigurationPriceMapperPluginInterface>
     */
    public function getProductConfigurationPriceMapperPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationShoppingListsRestApiDependencyProvider::PLUGINS_PRODUCT_CONFIGURATION_PRICE_MAPPER);
    }
}
