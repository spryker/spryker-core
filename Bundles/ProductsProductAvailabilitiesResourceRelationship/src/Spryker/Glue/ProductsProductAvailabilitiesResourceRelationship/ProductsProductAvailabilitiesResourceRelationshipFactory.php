<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship\Dependency\RestResource\ProductsResourceRelationToProductAvailabilitiesRestApiInterface;
use Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship\Processor\Expander\AbstractProductsProductAvailabilitiesResourceRelationshipExpander;
use Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship\Processor\Expander\AbstractProductsProductAvailabilitiesResourceRelationshipExpanderInterface;
use Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship\Processor\Expander\ConcreteProductsProductAvailabilitiesResourceRelationshipExpander;
use Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship\Processor\Expander\ConcreteProductsProductAvailabilitiesResourceRelationshipExpanderInterface;

class ProductsProductAvailabilitiesResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship\Processor\Expander\AbstractProductsProductAvailabilitiesResourceRelationshipExpanderInterface
     */
    public function createAbstractProductsProductAvailabilitiesResourceRelationshipExpander(): AbstractProductsProductAvailabilitiesResourceRelationshipExpanderInterface
    {
        return new AbstractProductsProductAvailabilitiesResourceRelationshipExpander($this->getProductAvailabilitiesResource());
    }

    /**
     * @return \Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship\Processor\Expander\ConcreteProductsProductAvailabilitiesResourceRelationshipExpanderInterface
     */
    public function createConcreteProductsProductAvailabilitiesResourceRelationshipExpander(): ConcreteProductsProductAvailabilitiesResourceRelationshipExpanderInterface
    {
        return new ConcreteProductsProductAvailabilitiesResourceRelationshipExpander($this->getProductAvailabilitiesResource());
    }

    /**
     * @return \Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship\Dependency\RestResource\ProductsResourceRelationToProductAvailabilitiesRestApiInterface
     */
    public function getProductAvailabilitiesResource(): ProductsResourceRelationToProductAvailabilitiesRestApiInterface
    {
        return $this->getProvidedDependency(ProductsProductAvailabilitiesResourceRelationshipDependencyProvider::RESOURCE_PRODUCT_AVAILABILITIES);
    }
}
