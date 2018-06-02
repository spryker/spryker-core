<?php

namespace Spryker\Zed\BusinessOnBehalf\Business\Model;

use Generated\Shared\Transfer\CompanyUserTransfer;

interface IsDefaultCompanyUserUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function setDefaultCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer;
}
