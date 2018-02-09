<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Persistence;

use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyUserWriterRepositoryInterface
{
    /**
     * Specification:
     * - Creates a user
     * - Finds a user by CompanyUserTransfer::idCompanyUser in the transfer
     * - Updates fields in a company user entity
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function save(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer;
}
