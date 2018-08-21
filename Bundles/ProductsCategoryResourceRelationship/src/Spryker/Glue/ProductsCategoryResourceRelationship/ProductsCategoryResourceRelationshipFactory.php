<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsCategoryResourceRelationship;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductsCategoryResourceRelationship\Dependency\RestResource\ProductsCategoryResourceRelationToCategoriesRestApiInterface;
use Spryker\Glue\ProductsCategoryResourceRelationship\Processor\Mapper\AbstractProductsCategoryResourceRelationshipMapper;
use Spryker\Glue\ProductsCategoryResourceRelationship\Processor\Mapper\AbstractProductsCategoryResourceRelationshipMapperInterface;
use Spryker\Glue\ProductsCategoryResourceRelationship\Processor\Mapper\ConcreteProductsCategoryResourceRelationshipMapper;
use Spryker\Glue\ProductsCategoryResourceRelationship\Processor\Mapper\ConcreteProductsCategoryResourceRelationshipMapperInterface;

class ProductsCategoryResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductsCategoryResourceRelationship\Dependency\RestResource\ProductsCategoryResourceRelationToCategoriesRestApiInterface
     */
    public function getCategoriesResource(): ProductsCategoryResourceRelationToCategoriesRestApiInterface
    {
        return $this->getProvidedDependency(ProductsCategoryResourceRelationshipDependencyProvider::RESOURCE_CATEGORY);
    }

    /**
     * @return \Spryker\Glue\ProductsCategoryResourceRelationship\Processor\Mapper\AbstractProductsCategoryResourceRelationshipMapperInterface
     */
    public function createAbstractProductsCategoryResourceRelationshipMapper(): AbstractProductsCategoryResourceRelationshipMapperInterface
    {
        return new AbstractProductsCategoryResourceRelationshipMapper($this->getCategoriesResource());
    }
}
