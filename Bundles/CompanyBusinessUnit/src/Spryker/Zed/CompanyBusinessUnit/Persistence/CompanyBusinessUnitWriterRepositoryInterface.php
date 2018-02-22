<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Persistence;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;

interface CompanyBusinessUnitWriterRepositoryInterface
{
    /**
     * Specification:
     * - Creates a company business unit
     * - Finds a company business unit by CompanyBusinessUnitTransfer::idCompanyBusinessUnit in the transfer
     * - Updates fields in a company business unit entity
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function save(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitTransfer;

    /**
     * Specification:
     * - Deletes a company business unit
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return void
     */
    public function delete(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): void;
}
