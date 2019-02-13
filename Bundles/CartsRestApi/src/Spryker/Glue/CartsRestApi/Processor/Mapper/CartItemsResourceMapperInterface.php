<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestCartItemRequestTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface CartItemsResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $cartItem
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string|null $uuidQuote
     *
     * @return \Generated\Shared\Transfer\RestCartItemRequestTransfer
     */
    public function createRestCartItemRequestTransfer(
        ItemTransfer $cartItem,
        RestRequestInterface $restRequest,
        ?string $uuidQuote
    ): RestCartItemRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\RestCartItemsAttributesTransfer
     */
    public function mapCartItemAttributes(ItemTransfer $itemTransfer): RestCartItemsAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function mapItemAttributesToItemTransfer(RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): ItemTransfer;
}
