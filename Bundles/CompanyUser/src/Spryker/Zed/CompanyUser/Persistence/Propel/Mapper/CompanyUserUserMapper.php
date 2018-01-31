<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUser;

class CompanyUserUserMapper implements CompanyUserMapperInterface
{
    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUser $companyUserEntity
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function mapCompanyUserEntityToTransfer(SpyCompanyUser $companyUserEntity): CompanyUserTransfer
    {
        $companyUserTransfer = new CompanyUserTransfer();
        $companyUserTransfer->fromArray($companyUserEntity->toArray());

        return $companyUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUser
     */
    public function mapCompanyUserTransferToEntity(CompanyUserTransfer $companyUserTransfer): SpyCompanyUser
    {
        $companyUserEntity = new SpyCompanyUser();
        $companyUserEntity->fromArray($companyUserTransfer->modifiedToArray());
        $companyUserEntity->setNew($companyUserTransfer->getIdCompanyUser() === null);

        return $companyUserEntity;
    }
}
