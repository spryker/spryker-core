<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductImageSetsResourceRelationship;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductsProductImageSetsResourceRelationship\Dependency\RestResource\ProductsProductImageSetsResourceRelationshipToProductImageSetsRestApiInterface;
use Spryker\Glue\ProductsProductImageSetsResourceRelationship\Processor\Expander\AbstractProductsProductImageSetsResourceRelationshipExpander;
use Spryker\Glue\ProductsProductImageSetsResourceRelationship\Processor\Expander\AbstractProductsProductImageSetsResourceRelationshipExpanderInterface;
use Spryker\Glue\ProductsProductImageSetsResourceRelationship\Processor\Expander\ConcreteProductsProductImageSetsResourceRelationshipExpander;
use Spryker\Glue\ProductsProductImageSetsResourceRelationship\Processor\Expander\ConcreteProductsProductImageSetsResourceRelationshipExpanderInterface;

class ProductsProductImageSetsResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductsProductImageSetsResourceRelationship\Processor\Expander\AbstractProductsProductImageSetsResourceRelationshipExpanderInterface
     */
    public function createAbstractProductsProductImageSetsResourceRelationshipExpander(): AbstractProductsProductImageSetsResourceRelationshipExpanderInterface
    {
        return new AbstractProductsProductImageSetsResourceRelationshipExpander($this->getProductImageSetsResource());
    }

    /**
     * @return \Spryker\Glue\ProductsProductImageSetsResourceRelationship\Processor\Expander\ConcreteProductsProductImageSetsResourceRelationshipExpanderInterface
     */
    public function createConcreteProductsProductImageSetsResourceRelationshipExpander(): ConcreteProductsProductImageSetsResourceRelationshipExpanderInterface
    {
        return new ConcreteProductsProductImageSetsResourceRelationshipExpander($this->getProductImageSetsResource());
    }

    /**
     * @return \Spryker\Glue\ProductsProductImageSetsResourceRelationship\Dependency\RestResource\ProductsProductImageSetsResourceRelationshipToProductImageSetsRestApiInterface
     */
    public function getProductImageSetsResource(): ProductsProductImageSetsResourceRelationshipToProductImageSetsRestApiInterface
    {
        return $this->getProvidedDependency(ProductsProductImageSetsResourceRelationshipDependencyProvider::RESOURCE_PRODUCT_IMAGE_SETS);
    }
}
