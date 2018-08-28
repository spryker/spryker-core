<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\Wishlists;

use Generated\Shared\Transfer\WishlistOverviewResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
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
     * @return \Generated\Shared\Transfer\WishlistTransfer|null
     */
    public function findWishlistByUuid(string $wishlistUuid): ?WishlistTransfer;

    /**
     * @param string $wishlistUuid
     *
     * @return \Generated\Shared\Transfer\WishlistOverviewResponseTransfer|null
     */
    public function findWishlistOverviewByUuid(string $wishlistUuid): ?WishlistOverviewResponseTransfer;
}
