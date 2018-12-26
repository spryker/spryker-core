<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\CompanyUser;

use Generated\Shared\Transfer\CompanyUserTransfer;
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
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findCompanyUser(string $companyUserUuid, string $customerReference): ?CompanyUserTransfer
    {
        $companyUserTransfer = $this->companyUserFacade->findCompanyUserByUuid(
            (new CompanyUserTransfer())->setUuid($companyUserUuid)
        );

        if (!$companyUserTransfer || ($companyUserTransfer->getCustomer()->getCustomerReference() !== $customerReference)) {
            return null;
        }

        return $companyUserTransfer;
    }
}
