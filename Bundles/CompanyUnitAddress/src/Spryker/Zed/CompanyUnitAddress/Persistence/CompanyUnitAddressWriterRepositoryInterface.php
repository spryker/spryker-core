<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

interface CompanyUnitAddressWriterRepositoryInterface
{
    /**
     * Specification:
     * - Creates a company unit address
     * - Finds a company unit address by CompanyUnitAddressTransfer::idCompanyUnitAddress in the transfer
     * - Updates fields in a company unit address entity
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function save(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressTransfer;

    /**
     * Specification:
     * - Finds a company unit address by CompanyUnitAddressTransfer::idCompanyUnitAddress in the transfer
     * - Deletes the company unit address
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    public function delete(CompanyUnitAddressTransfer $companyUnitAddressTransfer): void;
}
