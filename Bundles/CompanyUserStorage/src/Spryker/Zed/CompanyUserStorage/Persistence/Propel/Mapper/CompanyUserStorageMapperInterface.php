<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;
use Generated\Shared\Transfer\SpyCompanyUserStorageEntityTransfer;
use Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage;

interface CompanyUserStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer $companyUserStorageTransfer
     * @param \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage $spyCompanyUserEntityTransfer
     *
     * @return \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage
     */
    public function mapCompanyUserStorageTransferToCompanyUserStorageEntity(
        CompanyUserStorageTransfer $companyUserStorageTransfer,
        SpyCompanyUserStorage $spyCompanyUserEntityTransfer
    ): SpyCompanyUserStorage;

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUserStorageEntityTransfer $companyUserStorageEntityTransferTransfer
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer $companyUserStorageTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserStorageTransfer
     */
    public function mapCompanyUserStorageEntityToTransfer(
        SpyCompanyUserStorageEntityTransfer $companyUserStorageEntityTransferTransfer,
        CompanyUserStorageTransfer $companyUserStorageTransfer
    ): CompanyUserStorageTransfer;
}
