<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CatalogSearchProductsResourceRelationship;

use Spryker\Glue\CatalogSearchProductsResourceRelationship\Dependency\RestResource\CatalogSearchProductsResourceRelationshipToProductsRestApiInterface;
use Spryker\Glue\CatalogSearchProductsResourceRelationship\Processor\Expander\CatalogSearchProductsResourceRelationshipExpander;
use Spryker\Glue\CatalogSearchProductsResourceRelationship\Processor\Expander\CatalogSearchProductsResourceRelationshipExpanderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CatalogSearchProductsResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CatalogSearchProductsResourceRelationship\Processor\Expander\CatalogSearchProductsResourceRelationshipExpanderInterface
     */
    public function createCatalogSearchProductsResourceRelationshipExpander(): CatalogSearchProductsResourceRelationshipExpanderInterface
    {
        return new CatalogSearchProductsResourceRelationshipExpander($this->getProductsResource());
    }

    /**
     * @return \Spryker\Glue\CatalogSearchProductsResourceRelationship\Dependency\RestResource\CatalogSearchProductsResourceRelationshipToProductsRestApiInterface
     */
    public function getProductsResource(): CatalogSearchProductsResourceRelationshipToProductsRestApiInterface
    {
        return $this->getProvidedDependency(CatalogSearchProductsResourceRelationshipDependencyProvider::RESOURCE_PRODUCTS);
    }
}
