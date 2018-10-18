<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressToCompanyBusinessUnitEntityTransfer;

interface CompanyUnitAddressEntityManagerInterface
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
    public function saveCompanyUnitAddress(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressTransfer;

    /**
     * Specification:
     * - Deletes the company unit address by idCompanyUnitAddress
     *
     * @api
     *
     * @param int $idCompanyUnitAddress
     *
     * @return void
     */
    public function deleteCompanyUnitAddressById(int $idCompanyUnitAddress): void;

    /**
     * @param array $idAddresses
     * @param int $idCompanyBusinessUnit
     *
     * @return void
     */
    public function assignToCompanyBusinessUnit(array $idAddresses, int $idCompanyBusinessUnit): void;

    /**
     * @param array $idAddresses
     * @param int $idCompanyBusinessUnit
     *
     * @return void
     */
    public function unAssignFromCompanyBusinessUnit(array $idAddresses, int $idCompanyBusinessUnit): void;

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressToCompanyBusinessUnitEntityTransfer $companyUnitAddressToCompanyBusinessUnitEntityTransfer
     *
     * @return void
     */
    public function saveAddressToBusinessUnitRelation(SpyCompanyUnitAddressToCompanyBusinessUnitEntityTransfer $companyUnitAddressToCompanyBusinessUnitEntityTransfer): void;
}
