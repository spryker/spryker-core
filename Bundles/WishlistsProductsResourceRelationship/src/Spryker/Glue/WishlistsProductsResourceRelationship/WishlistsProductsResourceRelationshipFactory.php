<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsProductsResourceRelationship;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\WishlistsProductsResourceRelationship\Dependency\RestResource\WishlistsProductsResourceRelationshipToProductsRestApiInterface;
use Spryker\Glue\WishlistsProductsResourceRelationship\Processor\Mapper\WishlistsProductsResourceRelationshipMapper;
use Spryker\Glue\WishlistsProductsResourceRelationship\Processor\Mapper\WishlistsProductsResourceRelationshipMapperInterface;

class WishlistsProductsResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\WishlistsProductsResourceRelationship\Dependency\RestResource\WishlistsProductsResourceRelationshipToProductsRestApiInterface
     */
    public function getProductsResource(): WishlistsProductsResourceRelationshipToProductsRestApiInterface
    {
        return $this->getProvidedDependency(WishlistsProductsResourceRelationshipDependencyProvider::RESOURCE_PRODUCTS);
    }

    /**
     * @return \Spryker\Glue\WishlistsProductsResourceRelationship\Processor\Mapper\WishlistsProductsResourceRelationshipMapperInterface
     */
    public function createWishlistsProductsResourceRelationshipMapper(): WishlistsProductsResourceRelationshipMapperInterface
    {
        return new WishlistsProductsResourceRelationshipMapper(
            $this->getProductsResource()
        );
    }
}
