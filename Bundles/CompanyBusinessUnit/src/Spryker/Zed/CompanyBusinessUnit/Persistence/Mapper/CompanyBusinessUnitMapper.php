<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\SpyCompanyBusinessUnitEntityTransfer;

class CompanyBusinessUnitMapper implements CompanyBusinessUnitMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $businessUnitTransfer
     * @param \Generated\Shared\Transfer\SpyCompanyBusinessUnitEntityTransfer $businessUnitEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCompanyBusinessUnitEntityTransfer
     */
    public function mapBusinessUnitTransferToEntityTransfer(
        CompanyBusinessUnitTransfer $businessUnitTransfer,
        SpyCompanyBusinessUnitEntityTransfer $businessUnitEntityTransfer
    ): SpyCompanyBusinessUnitEntityTransfer {
        return $businessUnitEntityTransfer->fromArray($businessUnitTransfer->modifiedToArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyBusinessUnitEntityTransfer $businessUnitEntityTransfer
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $businessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function mapEntityTransferToBusinessUnitTransfer(
        SpyCompanyBusinessUnitEntityTransfer $businessUnitEntityTransfer,
        CompanyBusinessUnitTransfer $businessUnitTransfer
    ): CompanyBusinessUnitTransfer {
        $businessUnitTransfer->fromArray($businessUnitEntityTransfer->toArray(), true);
        if (!$businessUnitTransfer->getFkParentCompanyBusinessUnit()) {
            $businessUnitTransfer->setParentCompanyBusinessUnit(null);
        }

        return $businessUnitTransfer;
    }
}
