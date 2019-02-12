<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\SpyCompanyBusinessUnitEntityTransfer;
use Orm\Zed\Company\Persistence\SpyCompany;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit;

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
        $businessUnitEntityTransfer->fromArray($businessUnitTransfer->modifiedToArray(), true);
        $businessUnitEntityTransfer->setCompany(null);
        $businessUnitEntityTransfer->setParentCompanyBusinessUnit(null);

        return $businessUnitEntityTransfer;
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

    /**
     * @param \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit $companyBusinessUnitEntity
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function mapCompanyBusinessUnitEntityToCompanyBusinessUnitTransfer(
        SpyCompanyBusinessUnit $companyBusinessUnitEntity,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitTransfer {
        $companyBusinessUnitTransfer->fromArray(
            $companyBusinessUnitEntity->toArray(),
            true
        );
        $companyBusinessUnitTransfer->setCompany($this->mapCompanyEntityToCompanyTransfer(
            $companyBusinessUnitEntity->getCompany(),
            new CompanyTransfer()
        ));

        return $companyBusinessUnitTransfer;
    }

    /**
     * @param \Orm\Zed\Company\Persistence\SpyCompany $companyEntity
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    protected function mapCompanyEntityToCompanyTransfer(
        SpyCompany $companyEntity,
        CompanyTransfer $companyTransfer
    ): CompanyTransfer {
        return $companyTransfer->fromArray(
            $companyEntity->toArray(),
            true
        );
    }
}
