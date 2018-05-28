<?php

namespace Spryker\Zed\BusinessOnBehalf\Dependency\Facade;

use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyUserToBusinessOnBehalfFacadeInterface
{
    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCompanyUserById(int $idCompanyUser): CompanyUserTransfer;
}