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
}
