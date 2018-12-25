<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface ShoppingListItemRestResponseBuilderInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\RestErrorMessageTransfer[] $errors
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAddItemErrorResponse(ArrayObject $errors): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createShoppingListBadRequestErrorResponse(): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param string $idShoppingList
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createShoppingListItemResponse(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        string $idShoppingList
    ): RestResponseInterface;
}
