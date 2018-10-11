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
 * @method \Generated\Shared\Transfer\SpyCompanyUserEntityTransfer save(\Generated\Shared\Transfer\SpyCompanyUserEntityTransfer $spyCompanyUserEntityTransfer)
 */
class CompanyUserEntityManager extends AbstractEntityManager implements CompanyUserEntityManagerInterface
{
    protected const COLUMN_COMPANY_USER_IS_ACTIVE = 'IsActive';

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

        $newCompanyUserTransfer = $this->getFactory()
            ->createCompanyUserMapper()
            ->mapEntityTransferToCompanyUserTransfer($entityTransfer);

        return $companyUserTransfer->fromArray($newCompanyUserTransfer->modifiedToArray());
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
                static::COLUMN_COMPANY_USER_IS_ACTIVE => $companyUserTransfer->getIsActive(),
            ]);

        return $companyUserTransfer;
    }
}
