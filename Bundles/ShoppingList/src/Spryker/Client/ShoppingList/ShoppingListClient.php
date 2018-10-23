<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingList;

use Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListDismissRequestTransfer;
use Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListShareRequestTransfer;
use Generated\Shared\Transfer\ShoppingListShareResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\ShoppingList\Zed\ShoppingListStubInterface;

/**
 * @method \Spryker\Client\ShoppingList\ShoppingListFactory getFactory()
 */
class ShoppingListClient extends AbstractClient implements ShoppingListClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function createShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListResponseTransfer = $this->getZedStub()->createShoppingList($shoppingListTransfer);

        $this->getFactory()->getZedRequestClient()->addFlashMessagesFromLastZedRequest();
        $this->updatePermissions();

        return $shoppingListResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function updateShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListResponseTransfer = $this->getZedStub()->updateShoppingList($shoppingListTransfer);

        $this->getFactory()->getZedRequestClient()->addFlashMessagesFromLastZedRequest();
        $this->updatePermissions();

        return $shoppingListResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function removeShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListResponseTransfer = $this->getZedStub()->removeShoppingList($shoppingListTransfer);

        $this->getFactory()->getZedRequestClient()->addFlashMessagesFromLastZedRequest();
        $this->updatePermissions();

        return $shoppingListResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function clearShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListResponseTransfer = $this->getZedStub()->clearShoppingList($shoppingListTransfer);

        $this->getFactory()->getZedRequestClient()->addFlashMessagesFromLastZedRequest();

        return $shoppingListResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function addItem(ShoppingListItemTransfer $shoppingListItemTransfer, array $params = []): ShoppingListItemTransfer
    {
        $shoppingListItemTransfer = $this->getFactory()
            ->createShoppingListAddItemExpander()
            ->expandShoppingListAddItem($shoppingListItemTransfer, $params);

        $shoppingListItemTransfer = $this->getZedStub()->addItem($shoppingListItemTransfer);

        $this->getFactory()->getZedRequestClient()->addFlashMessagesFromLastZedRequest();
        $this->updatePermissions();

        return $shoppingListItemTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function removeItemById(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemResponseTransfer
    {
        $shoppingListItemResponseTransfer = $this->getZedStub()->removeItemById($shoppingListItemTransfer);

        $this->getFactory()->getZedRequestClient()->addFlashMessagesFromLastZedRequest();

        return $shoppingListItemResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function getShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        $shoppingListTransfer = $this->getZedStub()->getShoppingList($shoppingListTransfer);

        $this->getFactory()->getZedRequestClient()->addFlashMessagesFromLastZedRequest();

        return $shoppingListTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer
     */
    public function getShoppingListOverviewWithoutProductDetails(ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer): ShoppingListOverviewResponseTransfer
    {
        return $this->getZedStub()->getShoppingListOverview($shoppingListOverviewRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer
     */
    public function getShoppingListOverview(ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer): ShoppingListOverviewResponseTransfer
    {
        $shoppingListOverviewResponseTransfer = $this->getShoppingListOverviewWithoutProductDetails($shoppingListOverviewRequestTransfer);

        return $this->getFactory()->createProductStorage()->expandProductDetails($shoppingListOverviewResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getCustomerShoppingListCollection(): ShoppingListCollectionTransfer
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        return $this->getZedStub()->getCustomerShoppingListCollection($customerTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shoppingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function getShoppingListItemCollection(ShoppingListCollectionTransfer $shoppingListCollectionTransfer): ShoppingListItemCollectionTransfer
    {
        return $this->getZedStub()->getShoppingListItemCollection($shoppingListCollectionTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function getShoppingListItemCollectionTransfer(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): ShoppingListItemCollectionTransfer
    {
        return $this->getZedStub()->getShoppingListItemCollectionTransfer($shoppingListItemCollectionTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer $shoppingListAddToCartRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer
     */
    public function addItemCollectionToCart(ShoppingListAddToCartRequestCollectionTransfer $shoppingListAddToCartRequestCollectionTransfer): ShoppingListAddToCartRequestCollectionTransfer
    {
        return $this->getFactory()->createCartHandler()->addItemCollectionToCart($shoppingListAddToCartRequestCollectionTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function updateShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->getZedStub()->updateShoppingListItem($shoppingListItemTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer $shoppingListFromCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function createShoppingListFromQuote(ShoppingListFromCartRequestTransfer $shoppingListFromCartRequestTransfer): ShoppingListTransfer
    {
        $shoppingListResponseTransfer = $this->getZedStub()->createShoppingListFromQuote($shoppingListFromCartRequestTransfer);

        $this->getFactory()->getZedRequestClient()->addFlashMessagesFromLastZedRequest();
        $this->updatePermissions();

        return $shoppingListResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer
     */
    public function getShoppingListPermissionGroups(): ShoppingListPermissionGroupCollectionTransfer
    {
        return $this->getZedStub()->getShoppingListPermissionGroups();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function shareShoppingList(ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer): ShoppingListShareResponseTransfer
    {
        if ($shoppingListShareRequestTransfer->getIdCompanyBusinessUnit()) {
            return $this->getZedStub()->shareShoppingListWithCompanyBusinessUnit($shoppingListShareRequestTransfer);
        }
        if ($shoppingListShareRequestTransfer->getIdCompanyUser()) {
            return $this->getZedStub()->shareShoppingListWithCompanyUser($shoppingListShareRequestTransfer);
        }

        return (new ShoppingListShareResponseTransfer())->setIsSuccess(false);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function updateShoppingListSharedEntities(ShoppingListTransfer $shoppingListTransfer): ShoppingListShareResponseTransfer
    {
        return $this->getZedStub()->updateShoppingListSharedEntities($shoppingListTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListDismissRequestTransfer $shoppingListDismissRequest
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function dismissShoppingListSharing(ShoppingListDismissRequestTransfer $shoppingListDismissRequest): ShoppingListShareResponseTransfer
    {
        $shoppingListShareResponseTransfer = $this->getZedStub()->dismissShoppingListSharing($shoppingListDismissRequest);

        $this->updatePermissions();

        return $shoppingListShareResponseTransfer;
    }

    /**
     * @return \Spryker\Client\ShoppingList\Zed\ShoppingListStubInterface
     */
    protected function getZedStub(): ShoppingListStubInterface
    {
        return $this->getFactory()->createShoppingListStub();
    }

    /**
     * @return void
     */
    protected function updatePermissions(): void
    {
        $this->getFactory()->createPermissionUpdater()->updateCompanyUserPermissions();
    }
}
