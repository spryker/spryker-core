<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUser;

interface CompanyUserMapperInterface
{
    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUser $companyUserUserEntity
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function mapCompanyUserEntityToTransfer(SpyCompanyUser $companyUserUserEntity): CompanyUserTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserUserTransfer
     *
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUser
     */
    public function mapCompanyUserTransferToEntity(CompanyUserTransfer $companyUserUserTransfer): SpyCompanyUser;
}
