<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistItemsProductsResourceRelationship;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\WishlistItemsProductsResourceRelationship\Dependency\RestResource\WishlistItemsProductsResourceRelationshipToProductsRestApiInterface;
use Spryker\Glue\WishlistItemsProductsResourceRelationship\Processor\Expander\WishlistItemsProductsResourceRelationshipExpander;
use Spryker\Glue\WishlistItemsProductsResourceRelationship\Processor\Expander\WishlistItemsProductsResourceRelationshipExpanderInterface;

class WishlistItemsProductsResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\WishlistItemsProductsResourceRelationship\Processor\Expander\WishlistItemsProductsResourceRelationshipExpanderInterface
     */
    public function createWishlistsProductsResourceRelationshipExpander(): WishlistItemsProductsResourceRelationshipExpanderInterface
    {
        return new WishlistItemsProductsResourceRelationshipExpander(
            $this->getProductsResource()
        );
    }

    /**
     * @return \Spryker\Glue\WishlistItemsProductsResourceRelationship\Dependency\RestResource\WishlistItemsProductsResourceRelationshipToProductsRestApiInterface
     */
    public function getProductsResource(): WishlistItemsProductsResourceRelationshipToProductsRestApiInterface
    {
        return $this->getProvidedDependency(WishlistItemsProductsResourceRelationshipDependencyProvider::RESOURCE_PRODUCTS);
    }
}
