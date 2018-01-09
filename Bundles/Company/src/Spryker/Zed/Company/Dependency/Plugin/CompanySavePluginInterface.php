<?php

namespace Spryker\Zed\Company\Dependency;

use Generated\Shared\Transfer\CompanyTransfer;

interface CompanySavePluginInterface
{

    /**
     * CompanyTransfer $companyTransfer
     *
     * @return void
     */
    public function save(CompanyTransfer $companyTransfer);
}