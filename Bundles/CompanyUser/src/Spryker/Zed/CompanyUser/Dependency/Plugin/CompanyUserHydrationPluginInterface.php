<?php

namespace Spryker\Zed\CompanyUser\Dependency;

use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyUserHydrationPluginInterface
{
    /**
     * @param CompanyUserTransfer $companyUserTransfer
     *
     * @return CompanyUserTransfer
     */
    public function hydrate(CompanyUserTransfer $companyUserTransfer);
}