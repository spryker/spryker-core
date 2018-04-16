<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Persistence;

use Generated\Shared\Transfer\CompanyUserInvitationStatusTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationPersistenceFactory getFactory()
 */
class CompanyUserInvitationEntityManager extends AbstractEntityManager implements CompanyUserInvitationEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    public function saveCompanyUserInvitation(CompanyUserInvitationTransfer $companyUserInvitationTransfer): CompanyUserInvitationTransfer
    {
        $entityTransfer = $this->getFactory()
            ->createCompanyUserInvitationMapper()
            ->mapCompanyUserInvitationTransferToEntityTransfer($companyUserInvitationTransfer);

        $entityTransfer = $this->save($entityTransfer);

        return $this->getFactory()
            ->createCompanyUserInvitationMapper()
            ->mapEntityTransferToCompanyUserInvitationTransfer($entityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationStatusTransfer $companyUserInvitationStatusTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationStatusTransfer
     */
    public function saveCompanyUserInvitationStatus(
        CompanyUserInvitationStatusTransfer $companyUserInvitationStatusTransfer
    ): CompanyUserInvitationStatusTransfer {
        $entityTransfer = $this->getFactory()
            ->createCompanyUserInvitationStatusMapper()
            ->mapCompanyUserInvitationStatusTransferToEntityTransfer($companyUserInvitationStatusTransfer);

        $entityTransfer = $this->save($entityTransfer);

        return $this->getFactory()
            ->createCompanyUserInvitationStatusMapper()
            ->mapEntityTransferToCompanyUserInvitationStatusTransfer($entityTransfer);
    }

    /**
     * @param int $idCompanyUserInvitation
     *
     * @return void
     */
    public function deleteCompanyUserInvitationById(int $idCompanyUserInvitation): void
    {
        $this->getFactory()
            ->createCompanyUserInvitationQuery()
            ->filterByIdCompanyUserInvitation($idCompanyUserInvitation)
            ->delete();
    }
}
