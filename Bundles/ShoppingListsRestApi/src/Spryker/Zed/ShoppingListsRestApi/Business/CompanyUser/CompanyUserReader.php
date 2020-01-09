<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\CompanyUser;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ResponseMessageTransfer;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig as SharedShoppingListsRestApiConfig;
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
     * @param string $companyUserUuid
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function findCompanyUserByUuidAndCustomerReference(
        string $companyUserUuid,
        string $customerReference
    ): CompanyUserResponseTransfer {
        $companyUserTransfer = $this->companyUserFacade->findActiveCompanyUserByUuid(
            (new CompanyUserTransfer())->setUuid($companyUserUuid)
        );

        if (!$companyUserTransfer || ($companyUserTransfer->getCustomer()->getCustomerReference() !== $customerReference)) {
            return (new CompanyUserResponseTransfer())
                ->setIsSuccessful(false)
                ->addMessage(
                    (new ResponseMessageTransfer())
                        ->setText(SharedShoppingListsRestApiConfig::RESPONSE_CODE_COMPANY_USER_NOT_FOUND)
                );
        }

        return (new CompanyUserResponseTransfer())
            ->setIsSuccessful(true)
            ->setCompanyUser($companyUserTransfer);
    }
}
