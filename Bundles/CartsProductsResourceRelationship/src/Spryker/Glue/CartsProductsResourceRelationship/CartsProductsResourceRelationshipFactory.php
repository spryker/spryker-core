<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsProductsResourceRelationship;

use Spryker\Glue\CartsProductsResourceRelationship\Dependency\RestResource\CartsProductsResourceRelationToProductsRestApiInterface;
use Spryker\Glue\CartsProductsResourceRelationship\Processor\Mapper\CartsProductsResourceRelationshipMapper;
use Spryker\Glue\CartsProductsResourceRelationship\Processor\Mapper\CartsProductsResourceRelationshipMapperInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CartsProductsResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CartsProductsResourceRelationship\Dependency\RestResource\CartsProductsResourceRelationToProductsRestApiInterface
     */
    public function getProductsResource(): CartsProductsResourceRelationToProductsRestApiInterface
    {
        return $this->getProvidedDependency(CartsProductsResourceRelationshipDependencyProvider::RESOURCE_PRODUCTS);
    }

    /**
     * @return \Spryker\Glue\CartsProductsResourceRelationship\Processor\Mapper\CartsProductsResourceRelationshipMapperInterface
     */
    public function createCartsProductsResourceMapper(): CartsProductsResourceRelationshipMapperInterface
    {
        return new CartsProductsResourceRelationshipMapper($this->getProductsResource());
    }
}
