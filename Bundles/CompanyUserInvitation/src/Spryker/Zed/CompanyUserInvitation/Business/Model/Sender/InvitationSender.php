<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Sender;

use Generated\Shared\Transfer\CompanyUserInvitationAffectedReportTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConstants;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Mailer\InvitationMailerInterface;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Reader\InvitationReaderInterface;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Updater\InvitationUpdaterInterface;

class InvitationSender implements InvitationSenderInterface
{
    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Business\Model\Reader\InvitationReaderInterface
     */
    protected $invitationReader;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Business\Model\Updater\InvitationUpdaterInterface
     */
    protected $invitationUpdater;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Business\Model\Mailer\InvitationMailerInterface
     */
    protected $invitationMailer;

    /**
     * InvitationSender constructor.
     *
     * @param \Spryker\Zed\CompanyUserInvitation\Business\Model\Reader\InvitationReaderInterface $invitationReader
     * @param \Spryker\Zed\CompanyUserInvitation\Business\Model\Updater\InvitationUpdaterInterface $invitationUpdater
     * @param \Spryker\Zed\CompanyUserInvitation\Business\Model\Mailer\InvitationMailerInterface $invitationMailer
     */
    public function __construct(
        InvitationReaderInterface $invitationReader,
        InvitationUpdaterInterface $invitationUpdater,
        InvitationMailerInterface $invitationMailer
    ) {
        $this->invitationReader = $invitationReader;
        $this->invitationUpdater = $invitationUpdater;
        $this->invitationMailer = $invitationMailer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationAffectedReportTransfer
     */
    public function sendInvitations(
        CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUserInvitationAffectedReportTransfer {
        $companyUserInvitationAffectedReportTransfer = new CompanyUserInvitationAffectedReportTransfer();
        $companyUserInvitationCollection = $this->invitationReader->getCompanyUserInvitationCollection($criteriaFilterTransfer);
        $this->invitationUpdater->updateStatus($companyUserInvitationCollection, CompanyUserInvitationConstants::INVITATION_STATUS_PENDING);
        $this->invitationMailer->mailInvitations($companyUserInvitationCollection);
        $companyUserInvitationAffectedReportTransfer->setNumberOfAffectedInvitations(
            $companyUserInvitationCollection->getInvitations()->count()
        );

        return $companyUserInvitationAffectedReportTransfer;
    }
}
