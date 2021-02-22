<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Expander\ProductConfigurationProductConcreteExpander;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Expander\ProductConfigurationProductConcreteExpanderInterface;
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
     * @return \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface
     */
    public function getProductConfigurationStorageClient(): ProductConfigurationsRestApiToProductConfigurationStorageClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationsRestApiDependencyProvider::CLIENT_PRODUCT_CONFIGURATION_STORAGE);
    }
}
