<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestWishlistsAttributesTransfer;
use Generated\Shared\Transfer\WishlistOverviewResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface WishlistsResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistsTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapWishlistTransferToRestResource(WishlistTransfer $wishlistsTransfer): RestResourceInterface;

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer $wishlistOverviewResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapWishlistOverviewResponseTransferToRestResource(WishlistOverviewResponseTransfer $wishlistOverviewResponseTransfer): RestResourceInterface;

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     * @param \Generated\Shared\Transfer\RestWishlistsAttributesTransfer $attributesTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function mapWishlistAttributesToWishlistTransfer(WishlistTransfer $wishlistTransfer, RestWishlistsAttributesTransfer $attributesTransfer): WishlistTransfer;
}
