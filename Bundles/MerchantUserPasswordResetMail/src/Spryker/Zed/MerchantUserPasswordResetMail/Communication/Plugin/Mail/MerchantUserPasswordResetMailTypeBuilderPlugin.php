<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserPasswordResetMail\Communication\Plugin\Mail;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MailExtension\Dependency\Plugin\MailTypeBuilderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantUserPasswordResetMail\MerchantUserPasswordResetMailConfig getConfig()
 * @method \Spryker\Zed\MerchantUserPasswordResetMail\Communication\MerchantUserPasswordResetMailCommunicationFactory getFactory()
 */
class MerchantUserPasswordResetMailTypeBuilderPlugin extends AbstractPlugin implements MailTypeBuilderPluginInterface
{
    /**
     * @var string
     */
    protected const MAIL_TYPE = 'merchant restore password';

    /**
     * @var string
     */
    protected const MAIL_TEMPLATE_HTML = 'merchantUserPasswordResetMail/mail/merchant_restore_password.html.twig';

    /**
     * @var string
     */
    protected const MAIL_TEMPLATE_TEXT = 'merchantUserPasswordResetMail/mail/merchant_restore_password.text.twig';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MAIL_SUBJECT = 'mail.merchant.restore_password.subject';

    /**
     * {@inheritDoc}
     * - Returns the name of mail for merchant restore password mail.
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
     * - Builds the `MailTransfer` with data for merchant restore password mail.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function build(MailTransfer $mailTransfer): MailTransfer
    {
        /** @var \Generated\Shared\Transfer\UserTransfer $userTransfer */
        $userTransfer = $mailTransfer->getUserOrFail();

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
                    ->setName(sprintf('%s %s', $userTransfer->getFirstName(), $userTransfer->getLastName()))
                    ->setEmail($userTransfer->getUsernameOrFail()),
            );
    }
}
