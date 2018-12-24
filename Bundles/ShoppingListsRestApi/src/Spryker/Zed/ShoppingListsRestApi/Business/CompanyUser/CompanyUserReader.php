<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\CompanyUser;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToCompanyUserFacadeInterface;

class CompanyUserReader implements CompanyUserReaderInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToCompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(ShoppingListsRestApiToCompanyUserFacadeInterface $companyUserFacade)
    {
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findCompanyUserByUuid(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
    ): ?CompanyUserTransfer {
        $companyUserTransfer = $this->createCompanyUserTransfer($restShoppingListItemRequestTransfer);
        $companyUserTransfer = $this->companyUserFacade->findCompanyUserByUuid($companyUserTransfer);

        if (!$companyUserTransfer || !$this->isCustomerReferenceEqual($companyUserTransfer, $restShoppingListItemRequestTransfer)) {
            return null;
        }

        return $companyUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     *
     * @return bool
     */
    protected function isCustomerReferenceEqual(
        CompanyUserTransfer $companyUserTransfer,
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
    ): bool {
        $customerTransfer = $companyUserTransfer->getCustomer();
        $shoppingListItemTransfer = $restShoppingListItemRequestTransfer->getShoppingListItem();

        if (!$customerTransfer || !$shoppingListItemTransfer) {
            return false;
        }

        return $customerTransfer->getCustomerReference() === $shoppingListItemTransfer->getCustomerReference();
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
}
