<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListsRestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiFactory getFactory()
 */
class ShoppingListsRestApiClient extends AbstractClient implements ShoppingListsRestApiClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer
     */
    public function getCustomerShoppingListCollection(CustomerTransfer $customerTransfer): RestShoppingListCollectionResponseTransfer
    {
        return $this->getFactory()
            ->createShoppingListsRestApiStub()
            ->getCustomerShoppingListCollection($customerTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function createShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->getFactory()
            ->createShoppingListsRestApiStub()
            ->createShoppingList($shoppingListTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function updateShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->getFactory()
            ->createShoppingListsRestApiStub()
            ->updateShoppingList($shoppingListTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function deleteShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->getFactory()
            ->createShoppingListsRestApiStub()
            ->deleteShoppingList($shoppingListTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function addShoppingListItem(
        ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        return $this->getFactory()
            ->createShoppingListsRestApiStub()
            ->addShoppingListItem($shoppingListItemRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function deleteShoppingListItem(
        ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        return $this->getFactory()
            ->createShoppingListsRestApiStub()
            ->deleteShoppingListItem($shoppingListItemRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function updateShoppingListItem(
        ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        return $this->getFactory()
            ->createShoppingListsRestApiStub()
            ->updateShoppingListItem($shoppingListItemRequestTransfer);
    }
}
