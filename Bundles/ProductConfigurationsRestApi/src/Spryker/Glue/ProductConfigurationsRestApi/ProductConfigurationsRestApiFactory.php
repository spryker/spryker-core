<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Expander\ConcreteProductsProductConfigurationResourceExpander;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Expander\ConcreteProductsProductConfigurationResourceExpanderInterface;

class ProductConfigurationsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApi\Processor\Expander\ConcreteProductsProductConfigurationResourceExpanderInterface
     */
    public function createConcreteProductsProductConfigurationResourceExpander(): ConcreteProductsProductConfigurationResourceExpanderInterface
    {
        return new ConcreteProductsProductConfigurationResourceExpander($this->getProductConfigurationStorageClient());
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface
     */
    public function getProductConfigurationStorageClient(): ProductConfigurationsRestApiToProductConfigurationStorageClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationsRestApiDependencyProvider::CLIENT_PRODUCT_CONFIGURATIONS_STORAGE);
    }
}
