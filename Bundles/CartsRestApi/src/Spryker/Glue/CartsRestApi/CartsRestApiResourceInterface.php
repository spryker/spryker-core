<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestItemsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface CartsRestApiResourceInterface
{
    /**
     * Specification:
     * - Create `carts` REST response from `QuoteTransfer`.
     * - Sets payload to the `carts` resource.
     * - Adds `items` resource as relationship if `CartsRestApiConfig::getAllowedCartItemEagerRelationship()` is `true`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartRestResponse(QuoteTransfer $quoteTransfer, RestRequestInterface $restRequest): RestResponseInterface;

    /**
     * Specification:
     * - Create `guest-carts` REST response from `QuoteTransfer`.
     * - Sets payload to the `guest-carts` resource.
     * - Adds `guest-cart-items` resource as relationship if `CartsRestApiConfig::getAllowedGuestCartItemEagerRelationship()` is `true`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createGuestCartRestResponse(QuoteTransfer $quoteTransfer, RestRequestInterface $restRequest): RestResponseInterface;

    /**
     * Specification:
     * - Maps `ItemTransfer` to `RestItemsAttributesTransfer`.
     * - Runs `RestCartItemsAttributesMapperPluginInterface` plugins stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestItemsAttributesTransfer $restItemsAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestItemsAttributesTransfer
     */
    public function mapItemTransferToRestItemsAttributesTransfer(
        ItemTransfer $itemTransfer,
        RestItemsAttributesTransfer $restItemsAttributesTransfer,
        string $localeName
    ): RestItemsAttributesTransfer;
}
