<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\Mapper;

use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\ShoppingListsRestApi\ShoppingListsRestApiConfig;

class ShoppingListMapper implements ShoppingListMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListResponseTransfer $shoppingListResponseTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function mapShoppingListResponseTransferToShoppingListTransfer(
        ShoppingListResponseTransfer $shoppingListResponseTransfer,
        ShoppingListTransfer $shoppingListTransfer
    ): ShoppingListTransfer {
        $shoppingListTransfer
            ->setIdShoppingList($shoppingListResponseTransfer->getShoppingList()->getIdShoppingList());

        return $shoppingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListResponseTransfer $shoppingListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function mapShoppingListResponseErrorsToRestCodes(
        ShoppingListResponseTransfer $shoppingListResponseTransfer
    ): ShoppingListResponseTransfer {
        $errorCodes = [];
        foreach ($shoppingListResponseTransfer->getErrors() as $error) {
            $errorCodes[] = ShoppingListsRestApiConfig::getResponseErrorMapping()[$error] ?? $error;
        }

        $shoppingListResponseTransfer->setErrors($errorCodes);

        return $shoppingListResponseTransfer;
    }
}
