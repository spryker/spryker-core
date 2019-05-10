<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Company;

use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyTransfer;

interface CompanyClientInterface
{
    /**
     * Specification:
     * - Creates new company.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function createCompany(CompanyTransfer $companyTransfer): CompanyResponseTransfer;

    /**
     * Specification:
     *  - Retrieve a company by CompanyTransfer::idCompany in the transfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function getCompanyById(CompanyTransfer $companyTransfer): CompanyTransfer;

    /**
     * Specification:
     * - Finds a company by uuid.
     * - Makes zed request.
     * - Requires uuid field to be set in CompanyTransfer taken as parameter.
     *
     * @api
     *
     * {@internal will work if UUID field is provided.}
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function findCompanyByUuid(CompanyTransfer $companyTransfer): CompanyResponseTransfer;
}
