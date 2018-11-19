<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\BusinessUnitChecker;

use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyUserFacadeInterface;

class BusinessUnitChecker implements BusinessUnitCheckerInterface
{
    /**
     * @var \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(
        BusinessOnBehalfGuiToCompanyUserFacadeInterface $companyUserFacade
    ) {
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    public function checkBusinessUnitOfCompanyUserExist(CompanyUserTransfer $companyUserTransfer): bool
    {
        $companyUserCollection = $this->companyUserFacade->getCompanyUserCollection(
            (new CompanyUserCriteriaFilterTransfer())->setIdCompany($companyUserTransfer->getFkCompany())
        );

        foreach ($companyUserCollection->getCompanyUsers() as $companyUser) {
            if ($companyUser->getFkCustomer() !== $companyUserTransfer->getFkCustomer()) {
                continue;
            }

            if ($companyUser->getFkCompanyBusinessUnit() === $companyUserTransfer->getFkCompanyBusinessUnit()) {
                return true;
            }
        }

        return false;
    }
}
