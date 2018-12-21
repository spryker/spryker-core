<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToCompanyUserFacadeInterface;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface;

class ShoppingListItemAdder implements ShoppingListItemAdderInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface
     */
    protected $shoppingListFacade;

    /**
     * @param \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToCompanyUserFacadeInterface $companyUserFacade
     * @param \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade
     */
    public function __construct(
        ShoppingListsRestApiToCompanyUserFacadeInterface $companyUserFacade,
        ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade
    ) {
        $this->companyUserFacade = $companyUserFacade;
        $this->shoppingListFacade = $shoppingListFacade;
    }

    /***
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function addItem(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
    ): ShoppingListItemTransfer {
        $restShoppingListItemRequestTransfer->requireShoppingListItem()
            ->requireShoppingListUuid()
            ->requireCompanyUserUuid()
            ->requireCustomerReference();

        $companyUserTransfer = $this->createCompanyUserTransfer($restShoppingListItemRequestTransfer);
        $companyUserTransfer = $this->companyUserFacade->findCompanyUserByUuid($companyUserTransfer);

        $shoppingListTransfer = $this->createShoppingListTransfer(
            $restShoppingListItemRequestTransfer,
            $companyUserTransfer
        );

        $shoppingListResponseTransfer = $this->shoppingListFacade->findShoppingListByUuid($shoppingListTransfer);

        $shoppingListItemTransfer = $this->createShoppingListItemTransfer(
            $restShoppingListItemRequestTransfer,
            $companyUserTransfer,
            $shoppingListResponseTransfer->getShoppingList()
        );

        return $this->shoppingListFacade->addItem($shoppingListItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function createCompanyUserTransfer(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
    ): CompanyUserTransfer {
        $companyUserTransfer = (new CompanyUserTransfer())->setUuid(
            $restShoppingListItemRequestTransfer->getCompanyUserUuid()
        );

        return $companyUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function createShoppingListTransfer(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): ShoppingListTransfer {
        return (new ShoppingListTransfer())->setUuid($restShoppingListItemRequestTransfer->getShoppingListUuid())
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser());
    }

    /**
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function createShoppingListItemTransfer(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer,
        CompanyUserTransfer $companyUserTransfer,
        ShoppingListTransfer $shoppingListTransfer
    ): ShoppingListItemTransfer {
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())->fromArray(
            $restShoppingListItemRequestTransfer->getShoppingListItem()->toArray()
        );

        $shoppingListItemTransfer->setCustomerReference($restShoppingListItemRequestTransfer->getCustomerReference())
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->setFkShoppingList($shoppingListTransfer->getIdShoppingList());

        return $shoppingListItemTransfer;
    }
}
