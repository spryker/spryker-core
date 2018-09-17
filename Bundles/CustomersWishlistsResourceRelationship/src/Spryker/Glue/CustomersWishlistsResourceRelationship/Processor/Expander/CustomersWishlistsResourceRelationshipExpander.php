<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersWishlistsResourceRelationship\Processor\Expander;

use Spryker\Glue\CustomersWishlistsResourceRelationship\Dependency\RestResource\CustomersToWishlistsRestApiResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CustomersWishlistsResourceRelationshipExpander implements CustomersWishlistsResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\CustomersWishlistsResourceRelationship\Dependency\RestResource\CustomersToWishlistsRestApiResourceInterface
     */
    protected $wishlistsResource;

    /**
     * @param \Spryker\Glue\CustomersWishlistsResourceRelationship\Dependency\RestResource\CustomersToWishlistsRestApiResourceInterface $wishlistsResource
     */
    public function __construct(CustomersToWishlistsRestApiResourceInterface $wishlistsResource)
    {
        $this->wishlistsResource = $wishlistsResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $wishlistsResources = $this->wishlistsResource
                ->getCustomerWishlists($restRequest);
            foreach ($wishlistsResources as $wishlistsResource) {
                $resource->addRelationship($wishlistsResource);
            }
        }
    }
}
