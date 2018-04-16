<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingList\Zed;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListShareRequestTransfer;
use Generated\Shared\Transfer\ShoppingListShareResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToZedRequestClientInterface;

class ShoppingListStub implements ShoppingListStubInterface
{
    /**
     * @var \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ShoppingListToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->zedRequestClient->call('/shopping-list/gateway/create-shopping-list', $shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function updateShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->zedRequestClient->call('/shopping-list/gateway/update-shopping-list', $shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function removeShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->zedRequestClient->call('/shopping-list/gateway/remove-shopping-list', $shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function addItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->zedRequestClient->call('/shopping-list/gateway/add-item', $shoppingListItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function removeItemById(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemResponseTransfer
    {
        return $this->zedRequestClient->call('/shopping-list/gateway/remove-item-by-id', $shoppingListItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        return $this->zedRequestClient->call('/shopping-list/gateway/get-shopping-list', $shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getShoppingListOverview(ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer): ShoppingListOverviewResponseTransfer
    {
        return $this->zedRequestClient->call('/shopping-list/gateway/get-shopping-list-overview', $shoppingListOverviewRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getCustomerShoppingListCollection(CustomerTransfer $customerTransfer): ShoppingListCollectionTransfer
    {
        return $this->zedRequestClient->call('/shopping-list/gateway/get-customer-shopping-list-collection', $customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shoppingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getShoppingListItemCollection(ShoppingListCollectionTransfer $shoppingListCollectionTransfer): ShoppingListItemCollectionTransfer
    {
        return $this->zedRequestClient->call('/shopping-list/gateway/get-shopping-list-item-collection', $shoppingListCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getShoppingListItemCollectionTransfer(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): ShoppingListItemCollectionTransfer
    {
        return $this->zedRequestClient->call('/shopping-list/gateway/get-shopping-list-item-collection-transfer', $shoppingListItemCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function updateShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->zedRequestClient->call('/shopping-list/gateway/update-shopping-list-item', $shoppingListItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer $shoppingListFromCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createShoppingListFromQuote(ShoppingListFromCartRequestTransfer $shoppingListFromCartRequestTransfer): ShoppingListTransfer
    {
        return $this->zedRequestClient->call('/shopping-list/gateway/create-shopping-list-from-quote', $shoppingListFromCartRequestTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getShoppingListPermissionGroup(): ShoppingListPermissionGroupTransfer
    {
        return $this->zedRequestClient->call('/shopping-list/gateway/get-shopping-list-permission-group', new ShoppingListPermissionGroupTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function shareShoppingListWithCompanyBusinessUnit(ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer): ShoppingListShareResponseTransfer
    {
        return $this->zedRequestClient->call('/shopping-list/gateway/share-shopping-list-with-company-business-unit', $shoppingListShareRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function shareShoppingListWithCompanyUser(ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer): ShoppingListShareResponseTransfer
    {
        return $this->zedRequestClient->call('/shopping-list/gateway/share-shopping-list-with-company-user', $shoppingListShareRequestTransfer);
    }
}
