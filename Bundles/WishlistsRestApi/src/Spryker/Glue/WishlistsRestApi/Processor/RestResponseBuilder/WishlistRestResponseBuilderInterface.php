<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface WishlistRestResponseBuilderInterface
{
    /**
     * @param string $errorIdentifier
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createErrorResponseFromErrorIdentifier(string $errorIdentifier): RestResponseInterface;

    /**
     * @param string[] $errorIdentifiers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createErrorResponseFromErrorIdentifiers(array $errorIdentifiers): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer|null $wishlistTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createWishlistsRestResponse(?WishlistTransfer $wishlistTransfer = null): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createWishlistItemsResource(WishlistItemTransfer $wishlistItemTransfer): RestResourceInterface;

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createWishlistsResource(WishlistTransfer $wishlistTransfer): RestResourceInterface;
}
