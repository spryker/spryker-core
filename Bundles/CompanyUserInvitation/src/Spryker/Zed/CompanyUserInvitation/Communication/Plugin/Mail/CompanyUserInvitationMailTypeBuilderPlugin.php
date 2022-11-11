<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Communication\Plugin\Mail;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MailExtension\Dependency\Plugin\MailTypeBuilderPluginInterface;

/**
 * @method \Spryker\Zed\CompanyUserInvitation\CompanyUserInvitationConfig getConfig()
 * @method \Spryker\Zed\CompanyUserInvitation\Communication\CompanyUserInvitationCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyUserInvitation\Business\CompanyUserInvitationFacadeInterface getFacade()
 */
class CompanyUserInvitationMailTypeBuilderPlugin extends AbstractPlugin implements MailTypeBuilderPluginInterface
{
    /**
     * @var string
     */
    protected const MAIL_TYPE = 'company user invitation mail';

    /**
     * @var string
     */
    protected const MAIL_TEMPLATE_HTML = 'company-user-invitation/mail/invitation.html.twig';

    /**
     * @var string
     */
    protected const MAIL_TEMPLATE_TEXT = 'company-user-invitation/mail/invitation.text.twig';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MAIL_SUBJECT = 'mail.company.user.invitation.subject';

    /**
     * {@inheritDoc}
     * - Returns the name of mail for company user invitation mail.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::MAIL_TYPE;
    }

    /**
     * {@inheritDoc}
     * - Builds the `MailTransfer` with data for company user invitation mail.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function build(MailTransfer $mailTransfer): MailTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer */
        $companyUserInvitationTransfer = $mailTransfer->getCompanyUserInvitationOrFail();

        return $mailTransfer
            ->setSubject(static::GLOSSARY_KEY_MAIL_SUBJECT)
            ->addTemplate(
                (new MailTemplateTransfer())
                    ->setName(static::MAIL_TEMPLATE_HTML)
                    ->setIsHtml(true),
            )
            ->addTemplate(
                (new MailTemplateTransfer())
                    ->setName(static::MAIL_TEMPLATE_TEXT)
                    ->setIsHtml(false),
            )
            ->addRecipient(
                (new MailRecipientTransfer())
                    ->setEmail($companyUserInvitationTransfer->getEmailOrFail())
                    ->setName(sprintf('%s %s', $companyUserInvitationTransfer->getFirstName(), $companyUserInvitationTransfer->getLastName())),
            );
    }
}
