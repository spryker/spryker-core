<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem;

use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\RestShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\ShoppingListsRestApi\Business\CompanyUser\CompanyUserReaderInterface;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface;

class ShoppingListItemAdder implements ShoppingListItemAdderInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface
     */
    protected $shoppingListFacade;

    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Business\CompanyUser\CompanyUserReaderInterface
     */
    protected $companyUserReader;

    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemResponseTransferBuilderInterface
     */
    protected $shoppingListItemTransferResponseBuilder;

    /**
     * @param \Spryker\Zed\ShoppingListsRestApi\Business\CompanyUser\CompanyUserReaderInterface $companyUserReader
     * @param \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade
     * @param \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemResponseTransferBuilderInterface $shoppingListItemResponseTransferBuilder
     */
    public function __construct(
        CompanyUserReaderInterface $companyUserReader,
        ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade,
        ShoppingListItemResponseTransferBuilderInterface $shoppingListItemResponseTransferBuilder
    ) {
        $this->companyUserReader = $companyUserReader;
        $this->shoppingListFacade = $shoppingListFacade;
        $this->shoppingListItemTransferResponseBuilder = $shoppingListItemResponseTransferBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemResponseTransfer
     */
    public function addItem(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
    ): RestShoppingListItemResponseTransfer {
        $restShoppingListItemRequestTransfer->requireShoppingListItem()
            ->requireShoppingListUuid()
            ->requireCompanyUserUuid();

        $companyUserTransfer = $this->companyUserReader->findCompanyUser(
            $restShoppingListItemRequestTransfer->getCompanyUserUuid(),
            $restShoppingListItemRequestTransfer->getShoppingListItem()->getCustomerReference()
        );

        if (!$companyUserTransfer) {
            return $this->shoppingListItemTransferResponseBuilder->createCompanyUserNotFoundErrorResponseTransfer();
        }

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setUuid($restShoppingListItemRequestTransfer->getShoppingListUuid())
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser());

        $shoppingListResponseTransfer = $this->shoppingListFacade->findShoppingListByUuid($shoppingListTransfer);
        if (!$shoppingListResponseTransfer->getIsSuccess()) {
            return $this->shoppingListItemTransferResponseBuilder->createShoppingListNotFoundErrorResponseTransfer();
        }

        $restShoppingListItemRequestTransfer->getShoppingListItem()
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->setFkShoppingList($shoppingListTransfer->getIdShoppingList());

        $shoppingListItemTransfer = $this->shoppingListFacade->addItem($restShoppingListItemRequestTransfer->getShoppingListItem());
        if (!$shoppingListItemTransfer->getIdShoppingListItem()) {
            return $this->shoppingListItemTransferResponseBuilder->createShoppingListCanNotAddItemErrorResponseTransfer();
        }

        return $this->shoppingListItemTransferResponseBuilder
            ->createRestShoppingListItemResponseTransfer()
            ->setIsSuccess(true)
            ->setShoppingListItem($shoppingListItemTransfer);
    }
}
