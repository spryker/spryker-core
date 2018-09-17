<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersWishlistsResourceRelationship\Processor\Expander;

use Spryker\Glue\CustomersWishlistsResourceRelationship\Dependency\RestResource\CustomersToWishlistsRestApiInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CustomersWishlistsResourceRelationshipExpander implements CustomersWishlistsResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\CustomersWishlistsResourceRelationship\Dependency\RestResource\CustomersToWishlistsRestApiInterface
     */
    protected $wishlistsResource;

    /**
     * @param \Spryker\Glue\CustomersWishlistsResourceRelationship\Dependency\RestResource\CustomersToWishlistsRestApiInterface $wishlistsResource
     */
    public function __construct(CustomersToWishlistsRestApiInterface $wishlistsResource)
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
                ->findCustomerWishlists($restRequest);
            foreach ($wishlistsResources as $wishlistsResource) {
                $resource->addRelationship($wishlistsResource);
            }
        }
    }
}
