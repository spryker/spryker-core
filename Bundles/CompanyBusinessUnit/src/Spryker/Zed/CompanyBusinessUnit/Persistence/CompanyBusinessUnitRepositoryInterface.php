<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Persistence;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;

interface CompanyBusinessUnitRepositoryInterface
{
    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function getCompanyBusinessUnitById(
        int $idCompanyBusinessUnit
    ): CompanyBusinessUnitTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer $companyBusinessUnitCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function filterCompanyBusinessUnits(
        CompanyBusinessUnitCriteriaFilterTransfer $companyBusinessUnitCriteriaFilterTransfer
    ): CompanyBusinessUnitCollectionTransfer;

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return bool
     */
    public function hasUsers(int $idCompanyBusinessUnit): bool;
}
