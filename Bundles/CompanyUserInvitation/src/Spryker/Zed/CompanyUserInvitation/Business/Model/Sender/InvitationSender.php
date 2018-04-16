<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Sender;

use Exception;
use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationSendBatchResultTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationSendResultTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
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
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationSendResultTransfer
     */
    public function sendCompanyUserInvitation(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): CompanyUserInvitationSendResultTransfer {
        $companyUserInvitationSendResultTransfer = new CompanyUserInvitationSendResultTransfer();
        try {
            $companyUserInvitationTransfer->requireIdCompanyUserInvitation();
            $companyUserInvitationTransfer = $this->invitationReader->findCompanyUserInvitationById($companyUserInvitationTransfer);
            $companyUserInvitationSendResultTransfer->setSuccess($this->send($companyUserInvitationTransfer));
        } catch (Exception $e) {
            $companyUserInvitationSendResultTransfer->setSuccess(false);
        }

        return $companyUserInvitationSendResultTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationSendBatchResultTransfer
     */
    public function sendCompanyUserInvitations(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserInvitationSendBatchResultTransfer {
        $companyUserInvitationCollection = $this->createCompanyUserInvitationCollection(
            $companyUserTransfer,
            CompanyUserInvitationConstants::INVITATION_STATUS_NEW
        );

        $invitationsTotal = $invitationsFailed = 0;
        $companyUserRequestSendBatchResultTransfer = new CompanyUserInvitationSendBatchResultTransfer();
        foreach ($companyUserInvitationCollection->getInvitations() as $companyUserInvitationTransfer) {
            $invitationsTotal++;
            if (!$this->send($companyUserInvitationTransfer)) {
                $invitationsFailed++;
            }
        }
        $companyUserRequestSendBatchResultTransfer->setInvitationsTotal($invitationsTotal);
        $companyUserRequestSendBatchResultTransfer->setInvitationsFailed($invitationsFailed);

        return $companyUserRequestSendBatchResultTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return bool
     */
    protected function send(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): bool {
        $this->invitationMailer->mailInvitation($companyUserInvitationTransfer);
        $companyUserInvitationUpdateStatusRequestTransfer = (new CompanyUserInvitationUpdateStatusRequestTransfer())
            ->setCompanyUserInvitation($companyUserInvitationTransfer)
            ->setStatusKey(CompanyUserInvitationConstants::INVITATION_STATUS_PENDING);

        return $this->invitationUpdater->updateStatus($companyUserInvitationUpdateStatusRequestTransfer)->getSuccess();
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param string $statusKey
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    protected function createCompanyUserInvitationCollection(
        CompanyUserTransfer $companyUserTransfer,
        string $statusKey
    ): CompanyUserInvitationCollectionTransfer {
        $criteriaFilterTransfer = (new CompanyUserInvitationCriteriaFilterTransfer())
            ->setFkCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->setCompanyUserInvitationStatusKeyIn([$statusKey]);

        return $this->invitationReader->getCompanyUserInvitationCollection($criteriaFilterTransfer);
    }
}
