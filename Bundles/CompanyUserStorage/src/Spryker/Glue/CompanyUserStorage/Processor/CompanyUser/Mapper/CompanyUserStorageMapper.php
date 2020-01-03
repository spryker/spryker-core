<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserStorage\Processor\CompanyUser\Mapper;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

class CompanyUserStorageMapper implements CompanyUserStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer $companyUserStorageTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function mapCompanyUserStorageTransferToCompanyUserTransfer(
        CompanyUserStorageTransfer $companyUserStorageTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserTransfer {
        $companyUserTransfer->fromArray($companyUserStorageTransfer->toArray(), true)
            ->setFkCompany($companyUserStorageTransfer->getIdCompany())
            ->setFkCompanyBusinessUnit($companyUserStorageTransfer->getIdCompanyBusinessUnit());

        return $companyUserTransfer;
    }
}
