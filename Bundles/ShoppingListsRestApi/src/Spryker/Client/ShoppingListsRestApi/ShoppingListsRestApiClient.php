<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListsRestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer;
use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\RestShoppingListRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiFactory getFactory()
 */
class ShoppingListsRestApiClient extends AbstractClient implements ShoppingListsRestApiClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer
     */
    public function getCustomerShoppingListCollection(
        CustomerTransfer $customerTransfer
    ): RestShoppingListCollectionResponseTransfer {
        return $this->getFactory()
            ->createShoppingListsRestApiStub()
            ->getCustomerShoppingListCollection($customerTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestShoppingListRequestTransfer $restShoppingListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function findShoppingListByUuid(
        RestShoppingListRequestTransfer $restShoppingListRequestTransfer
    ): ShoppingListResponseTransfer {
        return $this->getFactory()
            ->createShoppingListsRestApiStub()
            ->findShoppingListByUuid($restShoppingListRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestShoppingListRequestTransfer $restShoppingListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function createShoppingList(
        RestShoppingListRequestTransfer $restShoppingListRequestTransfer
    ): ShoppingListResponseTransfer {
        return $this->getFactory()
            ->createShoppingListsRestApiStub()
            ->createShoppingList($restShoppingListRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestShoppingListRequestTransfer $restShoppingListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function updateShoppingList(
        RestShoppingListRequestTransfer $restShoppingListRequestTransfer
    ): ShoppingListResponseTransfer {
        return $this->getFactory()
            ->createShoppingListsRestApiStub()
            ->updateShoppingList($restShoppingListRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestShoppingListRequestTransfer $restShoppingListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function deleteShoppingList(
        RestShoppingListRequestTransfer $restShoppingListRequestTransfer
    ): ShoppingListResponseTransfer {
        return $this->getFactory()
            ->createShoppingListsRestApiStub()
            ->deleteShoppingList($restShoppingListRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function addItem(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        return $this->getFactory()
            ->createShoppingListsRestApiStub()
            ->addShoppingListItem($restShoppingListItemRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function deleteItem(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        return $this->getFactory()
            ->createShoppingListsRestApiStub()
            ->deleteShoppingListItem($restShoppingListItemRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function updateShoppingListItem(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        return $this->getFactory()
            ->createShoppingListsRestApiStub()
            ->updateShoppingListItem($restShoppingListItemRequestTransfer);
    }
}
