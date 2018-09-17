<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersWishlistsResourceRelationship\Dependency\RestResource;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CustomersToWishlistsRestApiResourceBridge implements CustomersToWishlistsRestApiResourceInterface
{
    /**
     * @var \Spryker\Glue\WishlistsRestApi\WishlistsRestApiResourceInterface
     */
    protected $wishlistResource;

    /**
     * @param \Spryker\Glue\WishlistsRestApi\WishlistsRestApiResourceInterface $wishlistResource
     */
    public function __construct($wishlistResource)
    {
        $this->wishlistResource = $wishlistResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getCustomerWishlists(RestRequestInterface $restRequest): array
    {
        return $this->wishlistResource->getCustomerWishlists($restRequest);
    }
}
