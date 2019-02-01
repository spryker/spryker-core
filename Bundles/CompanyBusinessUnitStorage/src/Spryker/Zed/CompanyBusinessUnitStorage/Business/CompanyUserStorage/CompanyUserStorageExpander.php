<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitStorage\Business\CompanyUserStorage;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

class CompanyUserStorageExpander implements CompanyUserStorageExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer $companyUserStorageTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserStorageTransfer
     */
    public function addCompanyBusinessUnitId(CompanyUserTransfer $companyUserTransfer, CompanyUserStorageTransfer $companyUserStorageTransfer): CompanyUserStorageTransfer
    {
        $companyUserStorageTransfer->setIdBusinessUnit($companyUserTransfer->getFkCompanyBusinessUnit());

        return $companyUserStorageTransfer;
    }
}
