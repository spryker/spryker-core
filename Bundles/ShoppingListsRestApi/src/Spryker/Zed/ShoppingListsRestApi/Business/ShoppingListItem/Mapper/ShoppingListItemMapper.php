<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\Mapper;

use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\RestShoppingListRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\ShoppingListsRestApi\ShoppingListsRestApiConfig;

class ShoppingListItemMapper implements ShoppingListItemMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListRequestTransfer $restShoppingListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListRequestTransfer
     */
    public function mapRestShoppingListItemRequestTransferToRestShoppingListRequestTransfer(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer,
        RestShoppingListRequestTransfer $restShoppingListRequestTransfer
    ): RestShoppingListRequestTransfer {
        return $restShoppingListRequestTransfer
            ->setCompanyUserUuid($restShoppingListItemRequestTransfer->getCompanyUserUuid())
            ->setCustomerReference($restShoppingListItemRequestTransfer->getShoppingListItem()->getCustomerReference())
            ->setShoppingList(
                (new ShoppingListTransfer())
                    ->setUuid($restShoppingListItemRequestTransfer->getShoppingListUuid())
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListResponseTransfer $shoppingListResponseTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function mapShoppingListResponseErrorsToShoppingListItemResponseErrors(
        ShoppingListResponseTransfer $shoppingListResponseTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer {
        return $shoppingListItemResponseTransfer->setIsSuccess(false)
            ->setErrors($shoppingListResponseTransfer->getErrors());
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function mapShoppingListResponseErrorsToRestCodes(
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer {
        $errorCodes = [];
        foreach ($shoppingListItemResponseTransfer->getErrors() as $error) {
            $errorCodes[] = ShoppingListsRestApiConfig::RESPONSE_ERROR_MAP[$error] ?? $error;
        }

        $shoppingListItemResponseTransfer->setErrors($errorCodes);

        return $shoppingListItemResponseTransfer;
    }
}
