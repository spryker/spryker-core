<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\Mapper;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\ShoppingListsRestApi\ShoppingListsRestApiConfig;

class ShoppingListMapper implements ShoppingListMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shoppingListCollectionTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer $restShoppingListCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer
     */
    public function mapShoppingListCollectionTransferToRestShoppingListCollectionResponseTransfer(
        ShoppingListCollectionTransfer $shoppingListCollectionTransfer,
        RestShoppingListCollectionResponseTransfer $restShoppingListCollectionResponseTransfer
    ): RestShoppingListCollectionResponseTransfer {
        return $restShoppingListCollectionResponseTransfer->setShoppingLists(
            $shoppingListCollectionTransfer->getShoppingLists()
        );
    }

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
            ->setUuid($shoppingListResponseTransfer->getShoppingList()->getUuid())
            ->setCustomerReference($shoppingListResponseTransfer->getShoppingList()->getCustomerReference())
            ->setIdCompanyUser($shoppingListResponseTransfer->getShoppingList()->getIdCompanyUser())
            ->setIdShoppingList($shoppingListResponseTransfer->getShoppingList()->getIdShoppingList());

        return $shoppingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param \Generated\Shared\Transfer\ShoppingListResponseTransfer $shoppingListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function mapCustomerResponseErrorsToShoppingListResponseErrors(
        CustomerResponseTransfer $customerResponseTransfer,
        ShoppingListResponseTransfer $shoppingListResponseTransfer
    ): ShoppingListResponseTransfer {
        $shoppingListResponseTransfer->setIsSuccess(false);

        foreach ($customerResponseTransfer->getErrors() as $customerErrorTransfer) {
            $shoppingListResponseTransfer->addError(
                $customerErrorTransfer->getMessage()
            );
        }

        return $shoppingListResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer $restShoppingListCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer
     */
    public function mapCustomerResponseErrorsToRestShoppingListCollectionResponseErrors(
        CustomerResponseTransfer $customerResponseTransfer,
        RestShoppingListCollectionResponseTransfer $restShoppingListCollectionResponseTransfer
    ): RestShoppingListCollectionResponseTransfer {
        foreach ($customerResponseTransfer->getErrors() as $customerErrorTransfer) {
            $restShoppingListCollectionResponseTransfer->addErrorCode(
                $customerErrorTransfer->getMessage()
            );
        }

        return $restShoppingListCollectionResponseTransfer;
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
            $errorCodes[] = ShoppingListsRestApiConfig::RESPONSE_ERROR_MAP[$error] ?? $error;
        }

        $shoppingListResponseTransfer->setErrors($errorCodes);

        return $shoppingListResponseTransfer;
    }
}
