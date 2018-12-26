<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface ShoppingListItemRestResponseBuilderInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\RestErrorMessageTransfer[] $restErrorMessages
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAddItemErrorResponse(ArrayObject $restErrorMessages): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createShoppingListBadRequestErrorResponse(): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
     * @param string $idShoppingList
     * @param string $idShoppingListItem
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createShoppingListItemResponse(
        RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer,
        string $idShoppingList,
        string $idShoppingListItem
    ): RestResponseInterface;
}
