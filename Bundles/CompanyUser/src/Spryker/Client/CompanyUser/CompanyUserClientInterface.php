<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUser;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyUserClientInterface
{
    /**
     * Specification:
     * - Creates new company user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function createCompanyUser(CompanyUserTransfer $companyUserUserTransfer): CompanyUserResponseTransfer;

    /**
     * Specification:
     * - Retrieve current company user information from session.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function getCompanyUser(): ?CompanyUserTransfer;
}
