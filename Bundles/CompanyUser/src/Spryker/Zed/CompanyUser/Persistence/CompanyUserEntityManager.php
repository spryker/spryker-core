<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Persistence;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CompanyUser\Persistence\CompanyUserPersistenceFactory getFactory()
 */
class CompanyUserEntityManager extends AbstractEntityManager implements CompanyUserEntityManagerInterface
{
    protected const IS_ACTIVE_COLUMN_SPY_COMPANY_USER = 'IsActive';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function saveCompanyUser(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserTransfer {
        $entityTransfer = $this->getFactory()
            ->createCompanyUserMapper()
            ->mapCompanyUserTransferToEntityTransfer($companyUserTransfer);
        $entityTransfer = $this->save($entityTransfer);

        return $this->getFactory()
            ->createCompanyUserMapper()
            ->mapEntityTransferToCompanyUserTransfer($entityTransfer);
    }

    /**
     * @api
     *
     * @param int $idCompanyUser
     *
     * @return void
     */
    public function deleteCompanyUserById(int $idCompanyUser): void
    {
        $this->getFactory()
            ->createCompanyUserQuery()
            ->filterByIdCompanyUser($idCompanyUser)
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function updateCompanyUserStatus(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserTransfer {
        $this->getFactory()
            ->createCompanyUserQuery()
            ->filterByIdCompanyUser(
                $companyUserTransfer->getIdCompanyUser()
            )->update([
                static::IS_ACTIVE_COLUMN_SPY_COMPANY_USER => $companyUserTransfer->getIsActive(),
            ]);

        return $companyUserTransfer;
    }
}
