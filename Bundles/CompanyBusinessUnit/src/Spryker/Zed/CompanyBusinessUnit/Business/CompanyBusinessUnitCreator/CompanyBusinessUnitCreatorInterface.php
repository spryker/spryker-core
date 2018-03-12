<?php

namespace Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitCreator;


use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyResponseTransfer;

interface CompanyBusinessUnitCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function create(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitResponseTransfer;

    /**
     * @param CompanyResponseTransfer $companyResponseTransfer
     *
     * @return CompanyResponseTransfer
     */
    public function createByCompany(CompanyResponseTransfer $companyResponseTransfer): CompanyResponseTransfer;
}