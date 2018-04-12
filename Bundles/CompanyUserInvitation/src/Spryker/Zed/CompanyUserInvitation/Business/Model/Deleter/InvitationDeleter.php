<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Deleter;

use Generated\Shared\Transfer\CompanyUserInvitationAffectedReportTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConstants;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Reader\InvitationReaderInterface;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Updater\InvitationUpdaterInterface;

class InvitationDeleter implements InvitationDeleterInterface
{
    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Business\Model\Reader\InvitationReaderInterface
     */
    private $invitationReader;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Business\Model\Updater\InvitationUpdaterInterface
     */
    private $invitationUpdater;

    /**
     * @param \Spryker\Zed\CompanyUserInvitation\Business\Model\Reader\InvitationReaderInterface $invitationReader
     * @param \Spryker\Zed\CompanyUserInvitation\Business\Model\Updater\InvitationUpdaterInterface $invitationUpdater
     */
    public function __construct(
        InvitationReaderInterface $invitationReader,
        InvitationUpdaterInterface $invitationUpdater
    ) {

        $this->invitationReader = $invitationReader;
        $this->invitationUpdater = $invitationUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationAffectedReportTransfer
     */
    public function deleteInvitations(
        CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUserInvitationAffectedReportTransfer {
        $companyUserInvitationAffectedReportTransfer = new CompanyUserInvitationAffectedReportTransfer();
        $companyUserInvitationCollection = $this->invitationReader->getCompanyUserInvitationCollection($criteriaFilterTransfer);
        $companyUserInvitationCollection = $this->anonymizeEmails($companyUserInvitationCollection);

        $this->invitationUpdater->updateStatus($companyUserInvitationCollection, CompanyUserInvitationConstants::INVITATION_STATUS_DELETED);

        $companyUserInvitationAffectedReportTransfer->setNumberOfAffectedInvitations(
            $companyUserInvitationCollection->getInvitations()->count()
        );

        return $companyUserInvitationAffectedReportTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    protected function anonymizeEmails(
        CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
    ): CompanyUserInvitationCollectionTransfer {
        foreach ($companyUserInvitationCollectionTransfer->getInvitations() as $companyUserInvitationTransfer) {
            $companyUserInvitationTransfer->setEmail($this->getRandomEmail());
        }

        return $companyUserInvitationCollectionTransfer;
    }

    /**
     * @return string
     */
    protected function getRandomEmail()
    {
        return sprintf(
            '%s@%s.%s',
            strtolower(md5(mt_rand())),
            strtolower(md5(mt_rand())),
            strtolower(md5(mt_rand()))
        );
    }
}
