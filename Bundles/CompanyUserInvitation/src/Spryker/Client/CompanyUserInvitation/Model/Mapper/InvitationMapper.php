<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserInvitation\Model\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Iterator;

class InvitationMapper implements InvitationMapperInterface
{
    const COLUMN_FIRST_NAME = 'first_name';
    const COLUMN_LAST_NAME = 'last_name';
    const COLUMN_EMAIL = 'email';
    const COLUMN_BUSINESS_UNIT = 'business_unit';

    /**
     * @param \Iterator $invitations
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    public function mapInvitations(Iterator $invitations): CompanyUserInvitationCollectionTransfer
    {
        $companyUserInvitationCollectionTransfer = new CompanyUserInvitationCollectionTransfer();
        foreach ($invitations as $invitation) {
            $companyUserInvitationTransfer = $this->getCompanyUserInvitationTransfer($invitation);
            $companyUserInvitationCollectionTransfer->addInvitation($companyUserInvitationTransfer);
        }

        return $companyUserInvitationCollectionTransfer;
    }

    /**
     * @param array $record
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    protected function getCompanyUserInvitationTransfer(array $record): CompanyUserInvitationTransfer
    {
        $invitationTransfer = new CompanyUserInvitationTransfer();
        $invitationTransfer->setFirstName($record[static::COLUMN_FIRST_NAME]);
        $invitationTransfer->setLastName($record[static::COLUMN_LAST_NAME]);
        $invitationTransfer->setEmail($record[static::COLUMN_EMAIL]);

        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->setName($record[static::COLUMN_BUSINESS_UNIT]);
        $invitationTransfer->setCompanyBusinessUnit($companyBusinessUnitTransfer);

        return $invitationTransfer;
    }
}
