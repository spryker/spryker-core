<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Persistence;

use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyUserEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function saveCompanyUser(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserTransfer;

    /**
     * @param int $idCompanyUser
     *
     * @return void
     */
    public function deleteCompanyUserById(int $idCompanyUser): void;

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function updateCompanyUserStatus(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserTransfer;
}
