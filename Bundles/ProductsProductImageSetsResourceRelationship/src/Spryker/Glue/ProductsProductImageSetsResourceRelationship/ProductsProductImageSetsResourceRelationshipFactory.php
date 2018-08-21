<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductImageSetsResourceRelationship;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductsProductImageSetsResourceRelationship\Dependency\RestResource\ProductsProductImageSetsResourceRelationshipToProductImageSetsRestApiInterface;
use Spryker\Glue\ProductsProductImageSetsResourceRelationship\Processor\Mapper\AbstractProductsProductImageSetsResourceRelationshipMapper;
use Spryker\Glue\ProductsProductImageSetsResourceRelationship\Processor\Mapper\AbstractProductsProductImageSetsResourceRelationshipMapperInterface;
use Spryker\Glue\ProductsProductImageSetsResourceRelationship\Processor\Mapper\ConcreteProductsProductImageSetsResourceRelationshipMapperInterface;
use Spryker\Glue\ProductsProductImageSetsResourceRelationship\Processor\Mapper\ConcreteProductsProductProductImageSetsResourceRelationshipMapper;

class ProductsProductImageSetsResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductsProductImageSetsResourceRelationship\Dependency\RestResource\ProductsProductImageSetsResourceRelationshipToProductImageSetsRestApiInterface
     */
    public function getProductImageSetsResource(): ProductsProductImageSetsResourceRelationshipToProductImageSetsRestApiInterface
    {
        return $this->getProvidedDependency(ProductsProductImageSetsResourceRelationshipDependencyProvider::RESOURCE_PRODUCT_IMAGE_SETS);
    }

    /**
     * @return \Spryker\Glue\ProductsProductImageSetsResourceRelationship\Processor\Mapper\AbstractProductsProductImageSetsResourceRelationshipMapperInterface
     */
    public function createAbstractProductsProductImageSetsResourceRelationshipMapper(): AbstractProductsProductImageSetsResourceRelationshipMapperInterface
    {
        return new AbstractProductsProductImageSetsResourceRelationshipMapper($this->getProductImageSetsResource());
    }

    /**
     * @return \Spryker\Glue\ProductsProductImageSetsResourceRelationship\Processor\Mapper\ConcreteProductsProductImageSetsResourceRelationshipMapperInterface
     */
    public function createConcreteProductsProductImageSetsResourceRelationshipMapper(): ConcreteProductsProductImageSetsResourceRelationshipMapperInterface
    {
        return new ConcreteProductsProductProductImageSetsResourceRelationshipMapper($this->getProductImageSetsResource());
    }
}
