<?php

namespace Spryker\Zed\Company\Dependency;

use Generated\Shared\Transfer\CompanyTransfer;

interface CompanySavePluginInterface
{

    /**
     * Specification:
     * -
     * CompanyTransfer $companyTransfer
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return void
     */
    public function save(CompanyTransfer $companyTransfer);
}