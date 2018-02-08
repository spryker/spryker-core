<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyBusinessUnit\Zed;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;

interface CompanyBusinessUnitStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createCompanyBusinessUnit(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function updateCompanyBusinessUnit(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return bool
     */
    public function deleteCompanyBusinessUnit(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): bool;

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $businessUnitCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getCompanyBusinessUnitCollection(
        CompanyBusinessUnitCollectionTransfer $businessUnitCollectionTransfer
    ): CompanyBusinessUnitCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getCompanyBusinessUnitById(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer;
}
