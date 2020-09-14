<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUsersRestApi\Business\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CompanyUsersRestApi\Dependency\Facade\CompanyUsersRestApiToCompanyUserFacadeInterface;

class CompanyUserExpander implements CompanyUserExpanderInterface
{
    /**
     * @var \Spryker\Zed\CompanyUsersRestApi\Dependency\Facade\CompanyUsersRestApiToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\CompanyUsersRestApi\Dependency\Facade\CompanyUsersRestApiToCompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(CompanyUsersRestApiToCompanyUserFacadeInterface $companyUserFacade)
    {
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteCustomerWithCompanyUser(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (
            !$quoteTransfer->getCustomer()->getCompanyUserTransfer()
            || !$quoteTransfer->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser()
        ) {
            return $quoteTransfer;
        }

        $idCompanyUser = $quoteTransfer->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser();
        $companyUserTransfer = $this->companyUserFacade->findCompanyUserById($idCompanyUser);

        $quoteTransfer->getCustomer()->setCompanyUserTransfer($companyUserTransfer);

        return $quoteTransfer;
    }
}
