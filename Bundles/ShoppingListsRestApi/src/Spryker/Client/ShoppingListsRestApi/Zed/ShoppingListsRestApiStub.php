<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListsRestApi\Zed;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer;
use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
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
     * @return \Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer
     */
    public function getCustomerShoppingListCollection(
        CustomerTransfer $customerTransfer
    ): RestShoppingListCollectionResponseTransfer {
        /** @var \Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer $restShoppingListCollectionResponseTransfer */
        $restShoppingListCollectionResponseTransfer = $this->zedRequestClient->call(
            '/shopping-lists-rest-api/gateway/get-customer-shopping-list-collection',
            $customerTransfer
        );

        return $restShoppingListCollectionResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\ShoppingListsRestApi\Communication\Controller\GatewayController::createShoppingList()
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
            $shoppingListTransfer
        );

        return $shoppingListResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\ShoppingListsRestApi\Communication\Controller\GatewayController::updateShoppingList()
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
            $shoppingListTransfer
        );

        return $shoppingListResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\ShoppingListsRestApi\Communication\Controller\GatewayController::deleteShoppingList()
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
            $shoppingListTransfer
        );

        return $shoppingListResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\ShoppingListsRestApi\Communication\Controller\GatewayController::addShoppingListItemAction()
     *
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function addShoppingListItem(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        /** @var \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer */
        $shoppingListItemResponseTransfer = $this->zedRequestClient->call(
            '/shopping-lists-rest-api/gateway/add-shopping-list-item',
            $restShoppingListItemRequestTransfer
        );

        return $shoppingListItemResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\ShoppingListsRestApi\Communication\Controller\GatewayController::deleteShoppingListItemAction()
     *
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function deleteShoppingListItem(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        /** @var \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer */
        $shoppingListItemResponseTransfer = $this->zedRequestClient->call(
            '/shopping-lists-rest-api/gateway/delete-shopping-list-item',
            $restShoppingListItemRequestTransfer
        );

        return $shoppingListItemResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\ShoppingListsRestApi\Communication\Controller\GatewayController::updateShoppingListItemAction()
     *
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function updateShoppingListItem(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        /** @var \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer */
        $shoppingListItemResponseTransfer = $this->zedRequestClient->call(
            '/shopping-lists-rest-api/gateway/update-shopping-list-item',
            $restShoppingListItemRequestTransfer
        );

        return $shoppingListItemResponseTransfer;
    }
}
