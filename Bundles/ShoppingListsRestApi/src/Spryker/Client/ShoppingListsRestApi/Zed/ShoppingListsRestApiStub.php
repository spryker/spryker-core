<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListsRestApi\Zed;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Client\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToZedRequestClientInterface;

class ShoppingListsRestApiStub implements ShoppingListsRestApiStubInterface
{
    /**
     * @var \Spryker\Client\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ShoppingListsRestApiToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @uses \Spryker\Zed\ShoppingListsRestApi\Communication\Controller\GatewayController::getCustomerShoppingListCollectionAction()
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getCustomerShoppingListCollection(
        CustomerTransfer $customerTransfer
    ): ShoppingListCollectionTransfer {
        /** @var \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shoppingListCollectionResponseTransfer */
        $shoppingListCollectionResponseTransfer = $this->zedRequestClient->call(
            '/shopping-lists-rest-api/gateway/get-customer-shopping-list-collection',
            $customerTransfer,
        );

        return $shoppingListCollectionResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\ShoppingListsRestApi\Communication\Controller\GatewayController::createShoppingListAction()
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function createShoppingList(
        ShoppingListTransfer $shoppingListTransfer
    ): ShoppingListResponseTransfer {
        /** @var \Generated\Shared\Transfer\ShoppingListResponseTransfer $shoppingListResponseTransfer */
        $shoppingListResponseTransfer = $this->zedRequestClient->call(
            '/shopping-lists-rest-api/gateway/create-shopping-list',
            $shoppingListTransfer,
        );

        return $shoppingListResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\ShoppingListsRestApi\Communication\Controller\GatewayController::updateShoppingListAction()
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function updateShoppingList(
        ShoppingListTransfer $shoppingListTransfer
    ): ShoppingListResponseTransfer {
        /** @var \Generated\Shared\Transfer\ShoppingListResponseTransfer $shoppingListResponseTransfer */
        $shoppingListResponseTransfer = $this->zedRequestClient->call(
            '/shopping-lists-rest-api/gateway/update-shopping-list',
            $shoppingListTransfer,
        );

        return $shoppingListResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\ShoppingListsRestApi\Communication\Controller\GatewayController::deleteShoppingListAction()
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function deleteShoppingList(
        ShoppingListTransfer $shoppingListTransfer
    ): ShoppingListResponseTransfer {
        /** @var \Generated\Shared\Transfer\ShoppingListResponseTransfer $shoppingListResponseTransfer */
        $shoppingListResponseTransfer = $this->zedRequestClient->call(
            '/shopping-lists-rest-api/gateway/delete-shopping-list',
            $shoppingListTransfer,
        );

        return $shoppingListResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\ShoppingListsRestApi\Communication\Controller\GatewayController::addShoppingListItemAction()
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function addShoppingListItem(
        ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        /** @var \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer */
        $shoppingListItemResponseTransfer = $this->zedRequestClient->call(
            '/shopping-lists-rest-api/gateway/add-shopping-list-item',
            $shoppingListItemRequestTransfer,
        );

        return $shoppingListItemResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\ShoppingListsRestApi\Communication\Controller\GatewayController::deleteShoppingListItemAction()
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function deleteShoppingListItem(
        ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        /** @var \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer */
        $shoppingListItemResponseTransfer = $this->zedRequestClient->call(
            '/shopping-lists-rest-api/gateway/delete-shopping-list-item',
            $shoppingListItemRequestTransfer,
        );

        return $shoppingListItemResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\ShoppingListsRestApi\Communication\Controller\GatewayController::updateShoppingListItemAction()
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function updateShoppingListItem(
        ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        /** @var \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer */
        $shoppingListItemResponseTransfer = $this->zedRequestClient->call(
            '/shopping-lists-rest-api/gateway/update-shopping-list-item',
            $shoppingListItemRequestTransfer,
        );

        return $shoppingListItemResponseTransfer;
    }
}
