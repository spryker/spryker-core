<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsCategoriesResourceRelationship;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\RestResource\ProductsCategoriesResourceRelationToCategoriesRestApiInterface;
use Spryker\Glue\ProductsCategoriesResourceRelationship\Processor\Expander\AbstractProductsCategoriesResourceRelationshipExpander;
use Spryker\Glue\ProductsCategoriesResourceRelationship\Processor\Expander\AbstractProductsCategoriesResourceRelationshipExpanderInterface;

class ProductsCategoriesResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\RestResource\ProductsCategoriesResourceRelationToCategoriesRestApiInterface
     */
    public function getCategoriesResource(): ProductsCategoriesResourceRelationToCategoriesRestApiInterface
    {
        return $this->getProvidedDependency(ProductsCategoriesResourceRelationshipDependencyProvider::RESOURCE_CATEGORY);
    }

    /**
     * @return \Spryker\Glue\ProductsCategoriesResourceRelationship\Processor\Mapper\AbstractProductsCategoriesResourceRelationshipExpanderInterface
     */
    public function createAbstractProductsCategoriesResourceRelationshipMapper(): AbstractProductsCategoriesResourceRelationshipExpanderInterface
    {
        return new AbstractProductsCategoriesResourceRelationshipExpander($this->getCategoriesResource());
    }
}
