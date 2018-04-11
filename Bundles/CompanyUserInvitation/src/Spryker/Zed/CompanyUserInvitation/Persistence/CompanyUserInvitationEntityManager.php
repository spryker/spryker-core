<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Persistence;

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
}
