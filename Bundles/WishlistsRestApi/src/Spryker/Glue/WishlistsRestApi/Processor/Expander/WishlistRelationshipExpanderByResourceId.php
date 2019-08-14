<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistReaderInterface;

class WishlistRelationshipExpanderByResourceId implements WishlistRelationshipExpanderByResourceIdInterface
{
    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistReaderInterface
     */
    protected $wishlistReader;

    /**
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistReaderInterface $wishlistReader
     */
    public function __construct(WishlistReaderInterface $wishlistReader)
    {
        $this->wishlistReader = $wishlistReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationshipsByResourceId(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $wishlistsResources = $this->wishlistReader
                ->getWishlistsByCustomerReference($resource->getId());
            foreach ($wishlistsResources as $wishlistsResource) {
                $resource->addRelationship($wishlistsResource);
            }
        }
    }
}
