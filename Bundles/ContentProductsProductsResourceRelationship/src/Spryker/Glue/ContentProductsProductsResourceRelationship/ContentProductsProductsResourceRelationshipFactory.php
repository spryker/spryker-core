<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductsProductsResourceRelationship;

use Spryker\Glue\ContentProductsProductsResourceRelationship\Dependency\RestResource\ContentProductsProductsResourceRelationshipToProductsResApiInterface;
use Spryker\Glue\ContentProductsProductsResourceRelationship\Processor\Expander\ContentProductsProductsResourceRelationshipExpander;
use Spryker\Glue\ContentProductsProductsResourceRelationship\Processor\Expander\ContentProductsProductsResourceRelationshipExpanderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class ContentProductsProductsResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ContentProductsProductsResourceRelationship\Processor\Expander\ContentProductsProductsResourceRelationshipExpanderInterface
     */
    public function createContentProductsProductsResourceRelationshipExpander(): ContentProductsProductsResourceRelationshipExpanderInterface
    {
        return new ContentProductsProductsResourceRelationshipExpander($this->getProductsResource());
    }

    /**
     * @return \Spryker\Glue\ContentProductsProductsResourceRelationship\Dependency\RestResource\ContentProductsProductsResourceRelationshipToProductsResApiInterface
     */
    public function getProductsResource(): ContentProductsProductsResourceRelationshipToProductsResApiInterface
    {
        return $this->getProvidedDependency(ContentProductsProductsResourceRelationshipDependencyProvider::RESOURCE_PRODUCTS);
    }
}
