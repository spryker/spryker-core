<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Sender;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationGetCollectionRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationSendBatchResponseTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationSendRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationSendResponseTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConfig;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Mailer\InvitationMailerInterface;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Reader\InvitationReaderInterface;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Updater\InvitationUpdaterInterface;
use Spryker\Zed\CompanyUserInvitation\Communication\Plugin\Permission\ManageCompanyUserInvitationPermissionPlugin;
use Spryker\Zed\Kernel\PermissionAwareTrait;

class InvitationSender implements InvitationSenderInterface
{
    use PermissionAwareTrait;

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
     * @param \Generated\Shared\Transfer\CompanyUserInvitationSendRequestTransfer $companyUserInvitationSendRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationSendResponseTransfer
     */
    public function sendCompanyUserInvitation(
        CompanyUserInvitationSendRequestTransfer $companyUserInvitationSendRequestTransfer
    ): CompanyUserInvitationSendResponseTransfer {
        $companyUserInvitationSendResponseTransfer = (new CompanyUserInvitationSendResponseTransfer())->setIsSuccess(false);

        if (!$this->can(ManageCompanyUserInvitationPermissionPlugin::KEY, $companyUserInvitationSendRequestTransfer->getIdCompanyUser())) {
            return $companyUserInvitationSendResponseTransfer;
        }

        $companyUserInvitationTransfer = $this->invitationReader->findCompanyUserInvitationById(
            $companyUserInvitationSendRequestTransfer->getCompanyUserInvitation()
        );

        if (!$companyUserInvitationTransfer) {
            return $companyUserInvitationSendResponseTransfer;
        }

        $companyUserInvitationSendResponseTransfer->setIsSuccess(
            $this->send($companyUserInvitationSendRequestTransfer->getIdCompanyUser(), $companyUserInvitationTransfer)
        );

        return $companyUserInvitationSendResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationSendBatchResponseTransfer
     */
    public function sendCompanyUserInvitations(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserInvitationSendBatchResponseTransfer {
        $companyUserRequestSendBatchResponseTransfer = (new CompanyUserInvitationSendBatchResponseTransfer())
            ->setIsSuccess(false);

        if (!$this->can(ManageCompanyUserInvitationPermissionPlugin::KEY, $companyUserTransfer->getIdCompanyUser())) {
            return $companyUserRequestSendBatchResponseTransfer;
        }

        $companyUserInvitationCollection = $this->createCompanyUserInvitationCollection(
            $companyUserTransfer,
            CompanyUserInvitationConfig::INVITATION_STATUS_NEW
        );

        $invitationsTotal = $invitationsFailed = 0;
        foreach ($companyUserInvitationCollection->getCompanyUserInvitations() as $companyUserInvitationTransfer) {
            $invitationsTotal++;
            if (!$this->send($companyUserTransfer->getIdCompanyUser(), $companyUserInvitationTransfer)) {
                $invitationsFailed++;
            }
        }

        $companyUserRequestSendBatchResponseTransfer->setInvitationsTotal($invitationsTotal);
        $companyUserRequestSendBatchResponseTransfer->setInvitationsFailed($invitationsFailed);
        $companyUserRequestSendBatchResponseTransfer->setIsSuccess(true);

        return $companyUserRequestSendBatchResponseTransfer;
    }

    /**
     * @param int $idCompanyUser
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return bool
     */
    protected function send(
        int $idCompanyUser,
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): bool {
        $this->invitationMailer->mailInvitation($companyUserInvitationTransfer);
        $companyUserInvitationUpdateStatusRequestTransfer = (new CompanyUserInvitationUpdateStatusRequestTransfer())
            ->setCompanyUserInvitation($companyUserInvitationTransfer)
            ->setIdCompanyUser($idCompanyUser)
            ->setStatusKey(CompanyUserInvitationConfig::INVITATION_STATUS_PENDING);

        return $this->invitationUpdater->updateStatus($companyUserInvitationUpdateStatusRequestTransfer)->getIsSuccess();
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

        $companyUserInvitationGetCollectionRequestTransfer = (new CompanyUserInvitationGetCollectionRequestTransfer())
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->setCriteriaFilter($criteriaFilterTransfer);

        return $this->invitationReader->getCompanyUserInvitationCollection($companyUserInvitationGetCollectionRequestTransfer);
    }
}
