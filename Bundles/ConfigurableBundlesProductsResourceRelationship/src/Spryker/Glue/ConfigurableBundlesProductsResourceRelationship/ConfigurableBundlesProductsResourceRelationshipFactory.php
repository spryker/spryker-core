<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesProductsResourceRelationship;

use Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToCatalogClientInterface;
use Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceInterface;
use Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Processor\Expander\ProductConcreteExpander;
use Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Processor\Expander\ProductConcreteExpanderInterface;
use Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Processor\Reader\ProductConcreteReader;
use Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Processor\Reader\ProductConcreteReaderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class ConfigurableBundlesProductsResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Processor\Expander\ProductConcreteExpanderInterface
     */
    public function createProductConcreteExpander(): ProductConcreteExpanderInterface
    {
        return new ProductConcreteExpander(
            $this->createProductConcreteReader(),
            $this->getProductsRestApiResource()
        );
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Processor\Reader\ProductConcreteReaderInterface
     */
    public function createProductConcreteReader(): ProductConcreteReaderInterface
    {
        return new ProductConcreteReader($this->getCatalogClient());
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceInterface
     */
    public function getProductsRestApiResource(): ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlesProductsResourceRelationshipDependencyProvider::RESOURCE_PRODUCTS_REST_API);
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToCatalogClientInterface
     */
    public function getCatalogClient(): ConfigurableBundlesProductsResourceRelationshipToCatalogClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlesProductsResourceRelationshipDependencyProvider::CLIENT_CATALOG);
    }
}
