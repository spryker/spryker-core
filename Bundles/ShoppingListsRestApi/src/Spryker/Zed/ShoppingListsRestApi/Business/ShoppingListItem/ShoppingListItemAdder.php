<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig;
use Spryker\Zed\ShoppingListsRestApi\Business\CompanyUser\CompanyUserReaderInterface;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface;
use Symfony\Component\HttpFoundation\Response;

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
     * @param \Spryker\Zed\ShoppingListsRestApi\Business\CompanyUser\CompanyUserReaderInterface $companyUserReader
     * @param \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade
     */
    public function __construct(
        CompanyUserReaderInterface $companyUserReader,
        ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade
    ) {
        $this->companyUserReader = $companyUserReader;
        $this->shoppingListFacade = $shoppingListFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function addItem(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        $restShoppingListItemRequestTransfer->requireShoppingListItem()
            ->requireShoppingListUuid()
            ->requireCompanyUserUuid();

        $companyUserTransfer = $this->companyUserReader->findCompanyUserByUuid($restShoppingListItemRequestTransfer);

        if (!$companyUserTransfer) {
            return $this->createCompanyUserNotFoundErrorResponse();
        }

        $shoppingListTransfer = $this->createShoppingListTransfer(
            $restShoppingListItemRequestTransfer,
            $companyUserTransfer
        );

        $shoppingListResponseTransfer = $this->shoppingListFacade->findShoppingListByUuid($shoppingListTransfer);

        if (!$shoppingListResponseTransfer->getIsSuccess()) {
            return $this->createShoppingListNotFoundErrorResponse();
        }

        $shoppingListItemTransfer = $this->createShoppingListItemTransfer(
            $restShoppingListItemRequestTransfer,
            $companyUserTransfer,
            $shoppingListResponseTransfer->getShoppingList()
        );

        $shoppingListItemTransfer = $this->shoppingListFacade->addItem($shoppingListItemTransfer);

        if (!$shoppingListItemTransfer->getIdShoppingListItem()) {
            return $this->createShoppingListCanNotAddItemErrorResponse();
        }

        return (new ShoppingListItemResponseTransfer())
            ->setIsSuccess(true)
            ->setShoppingListItem($shoppingListItemTransfer);
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
        $shoppingListItemTransfer = $restShoppingListItemRequestTransfer->getShoppingListItem();

        $shoppingListItemTransfer->setIdCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->setFkShoppingList($shoppingListTransfer->getIdShoppingList());

        return $shoppingListItemTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    protected function createShoppingListNotFoundErrorResponse(): ShoppingListItemResponseTransfer
    {
        return (new ShoppingListItemResponseTransfer())
            ->setIsSuccess(false)
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setCode(ShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_NOT_FOUND)
                    ->setDetail(ShoppingListsRestApiConfig::RESPONSE_DETAIL_SHOPPING_LIST_NOT_FOUND)
            );
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    protected function createCompanyUserNotFoundErrorResponse(): ShoppingListItemResponseTransfer
    {
        return (new ShoppingListItemResponseTransfer())
            ->setIsSuccess(false)
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setCode(ShoppingListsRestApiConfig::RESPONSE_CODE_COMPANY_USER_NOT_FOUND)
                    ->setDetail(ShoppingListsRestApiConfig::RESPONSE_DETAIL_COMPANY_USER_NOT_FOUND)
            );
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    protected function createShoppingListCanNotAddItemErrorResponse(): ShoppingListItemResponseTransfer
    {
        return (new ShoppingListItemResponseTransfer())
            ->setIsSuccess(false)
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                    ->setCode(ShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_CANNOT_ADD_ITEM)
                    ->setDetail(ShoppingListsRestApiConfig::RESPONSE_DETAIL_SHOPPING_LIST_CANNOT_ADD_ITEM)
            );
    }
}
