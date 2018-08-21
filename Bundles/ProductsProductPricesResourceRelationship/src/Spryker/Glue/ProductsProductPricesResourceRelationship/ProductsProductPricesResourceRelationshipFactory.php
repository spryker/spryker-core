<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductPricesResourceRelationship;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductsProductPricesResourceRelationship\Dependency\RestResource\ProductsProductPricesResourceRelationToProductPricesRestApiInterface;
use Spryker\Glue\ProductsProductPricesResourceRelationship\Processor\Mapper\AbstractProductsProductPricesResourceRelationshipMapper;
use Spryker\Glue\ProductsProductPricesResourceRelationship\Processor\Mapper\AbstractProductsProductPricesResourceRelationshipMapperInterface;
use Spryker\Glue\ProductsProductPricesResourceRelationship\Processor\Mapper\ConcreteProductsProductPricesResourceRelationshipMapper;
use Spryker\Glue\ProductsProductPricesResourceRelationship\Processor\Mapper\ConcreteProductsProductPricesResourceRelationshipMapperInterface;

class ProductsProductPricesResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductsProductPricesResourceRelationship\Dependency\RestResource\ProductsProductPricesResourceRelationToProductPricesRestApiInterface
     */
    public function getProductPricesResource(): ProductsProductPricesResourceRelationToProductPricesRestApiInterface
    {
        return $this->getProvidedDependency(ProductsProductPricesResourceRelationshipDependencyProvider::RESOURCE_PRODUCT_PRICES);
    }

    /**
     * @return \Spryker\Glue\ProductsProductPricesResourceRelationship\Processor\Mapper\AbstractProductsProductPricesResourceRelationshipMapperInterface
     */
    public function createAbstractProductsProductPricesResourceRelationshipMapper(): AbstractProductsProductPricesResourceRelationshipMapperInterface
    {
        return new AbstractProductsProductPricesResourceRelationshipMapper($this->getProductPricesResource());
    }

    /**
     * @return \Spryker\Glue\ProductsProductPricesResourceRelationship\Processor\Mapper\ConcreteProductsProductPricesResourceRelationshipMapperInterface
     */
    public function createConcreteProductsProductPricesResourceRelationshipMapper(): ConcreteProductsProductPricesResourceRelationshipMapperInterface
    {
        return new ConcreteProductsProductPricesResourceRelationshipMapper($this->getProductPricesResource());
    }
}
