<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\WishlistOverviewResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface WishlistsResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistsTransfer
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer|null $wishlistOverviewResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapWishlistsResource(WishlistTransfer $wishlistsTransfer, ?WishlistOverviewResponseTransfer $wishlistOverviewResponseTransfer = null): RestResourceInterface;
}
