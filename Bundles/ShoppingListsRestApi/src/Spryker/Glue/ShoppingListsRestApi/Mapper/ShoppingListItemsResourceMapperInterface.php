<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Mapper;

use Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer;
use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface ShoppingListItemsResourceMapperInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function mapRestRequestToShoppingListItemTransfer(
        RestRequestInterface $restRequest,
        RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
    ): ShoppingListItemTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer
     */
    public function mapShoppingListItemTransferToRestShoppingListItemAttributesTransfer(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
    ): RestShoppingListItemAttributesTransfer;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer
     */
    public function mapRestRequestToRestShoppingListItemRequestTransfer(
        RestRequestInterface $restRequest,
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer,
        RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
    ): RestShoppingListItemRequestTransfer;
}
