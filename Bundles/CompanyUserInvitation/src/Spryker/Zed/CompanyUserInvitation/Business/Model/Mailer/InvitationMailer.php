<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Mailer;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\CompanyUserInvitation\Communication\Plugin\Mail\CompanyUserInvitationMailTypePlugin;
use Spryker\Zed\CompanyUserInvitation\CompanyUserInvitationConfig;
use Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToMailFacadeInterface;
use SprykerShop\Shared\CompanyUserInvitationPage\CompanyUserInvitationPageConstants;

class InvitationMailer implements InvitationMailerInterface
{
    /**
     * @var \Spryker\Zed\CompanyUserInvitation\CompanyUserInvitationConfig
     */
    private $config;

    /**
     * @var \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToMailFacadeInterface
     */
    protected $mailFacade;

    /**
     * @param \Spryker\Zed\CompanyUserInvitation\CompanyUserInvitationConfig $config
     * @param \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToMailFacadeInterface $mailFacade
     */
    public function __construct(
        CompanyUserInvitationConfig $config,
        CompanyUserInvitationToMailFacadeInterface $mailFacade
    ) {
        $this->mailFacade = $mailFacade;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
     *
     * @return void
     */
    public function mailInvitations(
        CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
    ): void {
        foreach ($companyUserInvitationCollectionTransfer->getInvitations() as $companyUserInvitationTransfer) {
            $mailTransfer = $this->getMailTransfer($companyUserInvitationTransfer);
            $this->mailFacade->handleMail($mailTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function getMailTransfer(CompanyUserInvitationTransfer $companyUserInvitationTransfer): MailTransfer
    {
        $mailTransfer = new MailTransfer();
        $mailTransfer->setType(CompanyUserInvitationMailTypePlugin::MAIL_TYPE);
        $mailTransfer->setInvitationLink($this->getInvitationLink($companyUserInvitationTransfer));
        $mailTransfer->setCompanyUserInvitation($companyUserInvitationTransfer);

        return $mailTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return string
     */
    protected function getInvitationLink(CompanyUserInvitationTransfer $companyUserInvitationTransfer): string
    {
        return sprintf(
            '%s/%s?%s=%s',
            $this->config->getBaseUrl(),
            CompanyUserInvitationPageConstants::ROUTE_INVITATION_ACCEPT,
            CompanyUserInvitationPageConstants::INVITATION_HASH,
            $companyUserInvitationTransfer->getHash()
        );
    }
}
