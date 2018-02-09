<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit;

class CompanyBusinessUnitMapper implements CompanyBusinessUnitMapperInterface
{
    /**
     * @param \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit $companyBusinessUnitEntity
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function mapCompanyBusinessUnitEntityToTransfer(
        SpyCompanyBusinessUnit $companyBusinessUnitEntity
    ): CompanyBusinessUnitTransfer {
        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->fromArray($companyBusinessUnitEntity->toArray(), true);

        return $companyBusinessUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnit
     *
     * @return \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit
     */
    public function mapCompanyBusinessUnitTransferToEntity(
        CompanyBusinessUnitTransfer $companyBusinessUnit
    ): SpyCompanyBusinessUnit {
        $companyBusinessUnitEntity = new SpyCompanyBusinessUnit();
        $companyBusinessUnitEntity->fromArray($companyBusinessUnit->modifiedToArray());
        $companyBusinessUnitEntity->setNew($companyBusinessUnit->getIdCompanyBusinessUnit() === null);

        return $companyBusinessUnitEntity;
    }
}
