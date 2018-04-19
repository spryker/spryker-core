<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Communication\Plugin\Mail;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface;
use Spryker\Zed\Mail\Dependency\Plugin\MailTypePluginInterface;

/**
 * @method \Spryker\Zed\CompanyUserInvitation\Business\CompanyUserInvitationFacadeInterface getFacade()
 */
class CompanyUserInvitationMailTypePlugin extends AbstractPlugin implements MailTypePluginInterface
{
    public const MAIL_TYPE = 'company user invitation mail';

    protected const HTML_TEMPLATE = 'company-user-invitation/mail/invitation.html.twig';
    protected const TEXT_TEMPLATE = 'company-user-invitation/mail/invitation.text.twig';

    /**
     * @return string
     */
    public function getName()
    {
        return static::MAIL_TYPE;
    }

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return void
     */
    public function build(MailBuilderInterface $mailBuilder)
    {
        $this
            ->setSubject($mailBuilder)
            ->setHtmlTemplate($mailBuilder)
            ->setTextTemplate($mailBuilder)
            ->setSender($mailBuilder)
            ->setRecipient($mailBuilder);
    }

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return \Spryker\Zed\CompanyUserInvitation\Communication\Plugin\Mail\CompanyUserInvitationMailTypePlugin
     */
    protected function setSubject(MailBuilderInterface $mailBuilder): CompanyUserInvitationMailTypePlugin
    {
        $mailBuilder->setSubject('mail.company.user.invitation.subject');

        return $this;
    }

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return \Spryker\Zed\CompanyUserInvitation\Communication\Plugin\Mail\CompanyUserInvitationMailTypePlugin
     */
    protected function setHtmlTemplate(MailBuilderInterface $mailBuilder): CompanyUserInvitationMailTypePlugin
    {
        $mailBuilder->setHtmlTemplate(static::HTML_TEMPLATE);

        return $this;
    }

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return \Spryker\Zed\CompanyUserInvitation\Communication\Plugin\Mail\CompanyUserInvitationMailTypePlugin
     */
    protected function setTextTemplate(MailBuilderInterface $mailBuilder): CompanyUserInvitationMailTypePlugin
    {
        $mailBuilder->setTextTemplate(static::TEXT_TEMPLATE);

        return $this;
    }

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return \Spryker\Zed\CompanyUserInvitation\Communication\Plugin\Mail\CompanyUserInvitationMailTypePlugin
     */
    protected function setSender(MailBuilderInterface $mailBuilder): CompanyUserInvitationMailTypePlugin
    {
        $mailBuilder->setSender('mail.sender.email', 'mail.sender.name');

        return $this;
    }

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return \Spryker\Zed\CompanyUserInvitation\Communication\Plugin\Mail\CompanyUserInvitationMailTypePlugin
     */
    protected function setRecipient(MailBuilderInterface $mailBuilder): CompanyUserInvitationMailTypePlugin
    {
        $mailTransfer = $mailBuilder->getMailTransfer()->getCompanyUserInvitation();
        $mailBuilder->addRecipient(
            $mailTransfer->getEmail(),
            $mailTransfer->getFirstName() . ' ' . $mailTransfer->getLastName()
        );

        return $this;
    }
}
