<?php

namespace Spryker\Glue\WishlistsRestApi\Processor\Wishlists;

use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface WishlistsReaderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findWishlists(RestRequestInterface $restRequest): RestResponseInterface;

    /**
     * @param string $wishlistUuid
     *
     * @return null|\Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlistByUuid(string $wishlistUuid): ?WishlistTransfer;

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function getWishistResource(WishlistTransfer $wishlistTransfer): RestResourceInterface;
}
