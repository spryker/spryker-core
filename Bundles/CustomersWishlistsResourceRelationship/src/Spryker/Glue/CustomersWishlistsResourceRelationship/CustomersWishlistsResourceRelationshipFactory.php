<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersWishlistsResourceRelationship;

use Spryker\Glue\CustomersWishlistsResourceRelationship\Dependency\RestResource\CustomersToWishlistsRestApiInterface;
use Spryker\Glue\CustomersWishlistsResourceRelationship\Processor\Expander\CustomersWishlistsResourceRelationshipExpander;
use Spryker\Glue\CustomersWishlistsResourceRelationship\Processor\Expander\CustomersWishlistsResourceRelationshipExpanderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CustomersWishlistsResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CustomersWishlistsResourceRelationship\Processor\Expander\CustomersWishlistsResourceRelationshipExpanderInterface
     */
    public function createCustomersWishlistsResourceRelationshipExpander(): CustomersWishlistsResourceRelationshipExpanderInterface
    {
        return new CustomersWishlistsResourceRelationshipExpander($this->getWishlistResource());
    }

    /**
     * @return \Spryker\Glue\CustomersWishlistsResourceRelationship\Dependency\RestResource\CustomersToWishlistsRestApiInterface
     */
    public function getWishlistResource(): CustomersToWishlistsRestApiInterface
    {
        return $this->getProvidedDependency(CustomersWishlistsResourceRelationshipDependencyProvider::RESOURCE_WISHLISTS);
    }
}
