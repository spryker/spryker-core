<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistItemsProductsResourceRelationship;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\WishlistItemsProductsResourceRelationship\Dependency\RestResource\WishlistItemsProductsResourceRelationshipToProductsRestApiInterface;
use Spryker\Glue\WishlistItemsProductsResourceRelationship\Processor\Mapper\WishlistItemsProductsResourceRelationshipMapper;
use Spryker\Glue\WishlistItemsProductsResourceRelationship\Processor\Mapper\WishlistItemsProductsResourceRelationshipMapperInterface;

class WishlistItemsProductsResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\WishlistItemsProductsResourceRelationship\Dependency\RestResource\WishlistItemsProductsResourceRelationshipToProductsRestApiInterface
     */
    public function getProductsResource(): WishlistItemsProductsResourceRelationshipToProductsRestApiInterface
    {
        return $this->getProvidedDependency(WishlistItemsProductsResourceRelationshipDependencyProvider::RESOURCE_PRODUCTS);
    }

    /**
     * @return \Spryker\Glue\WishlistItemsProductsResourceRelationship\Processor\Mapper\WishlistItemsProductsResourceRelationshipMapperInterface
     */
    public function createWishlistsProductsResourceRelationshipMapper(): WishlistItemsProductsResourceRelationshipMapperInterface
    {
        return new WishlistItemsProductsResourceRelationshipMapper(
            $this->getProductsResource()
        );
    }
}
