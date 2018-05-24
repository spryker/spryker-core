<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Mailer;

use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConstants;
use Spryker\Zed\CompanyUserInvitation\Communication\Plugin\Mail\CompanyUserInvitationMailTypePlugin;
use Spryker\Zed\CompanyUserInvitation\CompanyUserInvitationConfig;
use Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToMailFacadeInterface;

class InvitationMailer implements InvitationMailerInterface
{
    /**
     * @var \Spryker\Zed\CompanyUserInvitation\CompanyUserInvitationConfig
     */
    protected $config;

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
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return void
     */
    public function mailInvitation(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): void {
        $mailTransfer = (new MailTransfer())
            ->setType(CompanyUserInvitationMailTypePlugin::MAIL_TYPE)
            ->setInvitationLink($this->getInvitationLink($companyUserInvitationTransfer))
            ->setCompanyUserInvitation($companyUserInvitationTransfer);
        
        $this->mailFacade->handleMail($mailTransfer);
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
            CompanyUserInvitationConstants::ROUTE_INVITATION_ACCEPT,
            CompanyUserInvitationConstants::INVITATION_HASH,
            $companyUserInvitationTransfer->getHash()
        );
    }
}
