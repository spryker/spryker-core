<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Persistence;

use Generated\Shared\Transfer\CompanyUserInvitationStatusTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Orm\Zed\CompanyUserInvitation\Persistence\SpyCompanyUserInvitationStatus;
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
        $spyCompanyUserInvitation = $this->getFactory()
            ->createCompanyUserInvitationQuery()
            ->filterByIdCompanyUserInvitation($companyUserInvitationTransfer->getIdCompanyUserInvitation())
            ->findOneOrCreate();

        $spyCompanyUserInvitation->fromArray($companyUserInvitationTransfer->modifiedToArray());
        $spyCompanyUserInvitation->save();

        return $this->getFactory()
            ->createCompanyUserInvitationMapper()
            ->mapSpyCompanyUserInvitationToCompanyUserInvitationTransfer($spyCompanyUserInvitation);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationStatusTransfer $companyUserInvitationStatusTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationStatusTransfer
     */
    public function saveCompanyUserInvitationStatus(
        CompanyUserInvitationStatusTransfer $companyUserInvitationStatusTransfer
    ): CompanyUserInvitationStatusTransfer {
        $spyCompanyUserInvitationStatus = new SpyCompanyUserInvitationStatus();
        $spyCompanyUserInvitationStatus->fromArray($companyUserInvitationStatusTransfer->toArray());
        $spyCompanyUserInvitationStatus->save();

        return $companyUserInvitationStatusTransfer->fromArray($spyCompanyUserInvitationStatus->toArray(), true);
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
