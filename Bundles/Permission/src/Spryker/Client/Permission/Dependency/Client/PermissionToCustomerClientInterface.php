<?php

namespace Spryker\Client\Permission\Dependency\Client;


use Generated\Shared\Transfer\CompanyUserTransfer;

interface PermissionToCustomerClientInterface
{
    /**
     * @example Direct dependency to the CompanyUser will be removed
     *
     * @return CompanyUserTransfer
     */
    public function getCompanyUser();
}