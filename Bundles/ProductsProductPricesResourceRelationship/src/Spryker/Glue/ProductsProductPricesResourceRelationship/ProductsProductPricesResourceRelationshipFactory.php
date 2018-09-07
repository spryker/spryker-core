<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductPricesResourceRelationship;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductsProductPricesResourceRelationship\Dependency\RestResource\ProductsProductPricesResourceRelationToProductPricesRestApiInterface;
use Spryker\Glue\ProductsProductPricesResourceRelationship\Processor\Expander\AbstractProductsProductPricesResourceRelationshipExpander;
use Spryker\Glue\ProductsProductPricesResourceRelationship\Processor\Expander\AbstractProductsProductPricesResourceRelationshipExpanderInterface;
use Spryker\Glue\ProductsProductPricesResourceRelationship\Processor\Expander\ConcreteProductsProductPricesResourceRelationshipExpander;
use Spryker\Glue\ProductsProductPricesResourceRelationship\Processor\Expander\ConcreteProductsProductPricesResourceRelationshipExpanderInterface;

class ProductsProductPricesResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductsProductPricesResourceRelationship\Processor\Expander\AbstractProductsProductPricesResourceRelationshipExpanderInterface
     */
    public function createAbstractProductsProductPricesResourceRelationshipExpander(): AbstractProductsProductPricesResourceRelationshipExpanderInterface
    {
        return new AbstractProductsProductPricesResourceRelationshipExpander($this->getProductPricesResource());
    }

    /**
     * @return \Spryker\Glue\ProductsProductPricesResourceRelationship\Processor\Expander\ConcreteProductsProductPricesResourceRelationshipExpanderInterface
     */
    public function createConcreteProductsProductPricesResourceRelationshipExpander(): ConcreteProductsProductPricesResourceRelationshipExpanderInterface
    {
        return new ConcreteProductsProductPricesResourceRelationshipExpander($this->getProductPricesResource());
    }

    /**
     * @return \Spryker\Glue\ProductsProductPricesResourceRelationship\Dependency\RestResource\ProductsProductPricesResourceRelationToProductPricesRestApiInterface
     */
    public function getProductPricesResource(): ProductsProductPricesResourceRelationToProductPricesRestApiInterface
    {
        return $this->getProvidedDependency(ProductsProductPricesResourceRelationshipDependencyProvider::RESOURCE_PRODUCT_PRICES);
    }
}
