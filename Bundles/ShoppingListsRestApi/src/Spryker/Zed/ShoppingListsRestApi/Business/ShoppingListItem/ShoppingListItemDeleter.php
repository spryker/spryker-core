<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem;

use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig as SharedShoppingListsRestApiConfig;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface;

class ShoppingListItemDeleter implements ShoppingListItemDeleterInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface
     */
    protected $shoppingListFacade;

    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemReaderInterface
     */
    protected $shoppingListItemReader;

    /**
     * @param \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade
     * @param \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemReaderInterface $shoppingListItemReader
     */
    public function __construct(
        ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade,
        ShoppingListItemReaderInterface $shoppingListItemReader
    ) {
        $this->shoppingListFacade = $shoppingListFacade;
        $this->shoppingListItemReader = $shoppingListItemReader;
    }

    /**
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function deleteItem(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {

        $restShoppingListItemRequestTransfer
            ->requireShoppingListItem()
            ->requireShoppingListUuid()
            ->requireCompanyUserUuid();
        $restShoppingListItemRequestTransfer->getShoppingListItem()
            ->requireUuid()
            ->requireCustomerReference();

        $shoppingListItemResponseTransfer = $this->shoppingListItemReader->findShoppingListItem(
            $restShoppingListItemRequestTransfer
        );

        if ($shoppingListItemResponseTransfer->getIsSuccess() === false) {
            return $shoppingListItemResponseTransfer;
        }

        $shoppingListItemResponseTransfer = $this->shoppingListFacade->removeItemById($shoppingListItemResponseTransfer->getShoppingListItem());

        if ($shoppingListItemResponseTransfer->getIsSuccess() === false) {
            return $shoppingListItemResponseTransfer->addError(SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_CANNOT_DELETE_ITEM);
        }

        return $shoppingListItemResponseTransfer;
    }
}
