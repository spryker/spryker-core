<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit;

interface CompanyBusinessUnitMapperInterface
{
    /**
     * @param \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit $companyBusinessUnitEntity
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function mapCompanyBusinessUnitEntityToTransfer(
        SpyCompanyBusinessUnit $companyBusinessUnitEntity
    ): CompanyBusinessUnitTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyTransfer
     *
     * @return \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit
     */
    public function mapCompanyBusinessUnitTransferToEntity(
        CompanyBusinessUnitTransfer $companyTransfer
    ): SpyCompanyBusinessUnit;
}
