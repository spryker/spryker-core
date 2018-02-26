<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business\Model;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;

interface CompanyBusinessUnitReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $businessUnitCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function getCompanyBusinessUnitCollection(
        CompanyBusinessUnitCollectionTransfer $businessUnitCollectionTransfer
    ): CompanyBusinessUnitCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function getCompanyBusinessUnitById(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer;
}
