<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Persistence;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;

interface CompanyUserStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer $companyUserStorageTransfer
     *
     * @return void
     */
    public function saveCompanyUserStorage(CompanyUserStorageTransfer $companyUserStorageTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer $companyUserStorageTransfer
     *
     * @return void
     */
    public function deleteCompanyUserStorage(CompanyUserStorageTransfer $companyUserStorageTransfer): void;
}
