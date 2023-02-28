<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Communication\Plugin\Mail;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MailExtension\Dependency\Plugin\MailTypeBuilderPluginInterface;

/**
 * @method \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig getConfig()
 * @method \Spryker\Zed\AvailabilityNotification\Communication\AvailabilityNotificationCommunicationFactory getFactory()
 * @method \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacadeInterface getFacade()
 */
class AvailabilityNotificationMailTypeBuilderPlugin extends AbstractPlugin implements MailTypeBuilderPluginInterface
{
    /**
     * @var string
     */
    protected const MAIL_TYPE = 'AVAILABILITY_NOTIFICATION_MAIL';

    /**
     * @var string
     */
    protected const MAIL_TEMPLATE_HTML = 'AvailabilityNotification/mail/notification.html.twig';

    /**
     * @var string
     */
    protected const MAIL_TEMPLATE_TEXT = 'AvailabilityNotification/mail/notification.text.twig';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MAIL_SUBJECT = 'availability_notification_subscription.mail.notification.subject';

    /**
     * @var string
     */
    protected const PARAMETER_NAME = '%name%';

    /**
     * {@inheritDoc}
     * - Returns the name of mail for availability notification mail.
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
     * - Requires `Mail.availabilityNotificationSubscriptionMailData.availabilityNotificationSubscription` to be set.
     * - Requires `Mail.availabilityNotificationSubscriptionMailData.productName` to be set.
     * - Builds the `MailTransfer` with data for availability notification mail.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function build(MailTransfer $mailTransfer): MailTransfer
    {
        /** @var \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer */
        $availabilityNotificationSubscriptionTransfer = $mailTransfer
            ->getAvailabilityNotificationSubscriptionMailDataOrFail()
            ->getAvailabilityNotificationSubscriptionOrFail();

        $productName = $mailTransfer
            ->getAvailabilityNotificationSubscriptionMailDataOrFail()
            ->getProductNameOrFail();

        return $mailTransfer
            ->setSubject(static::GLOSSARY_KEY_MAIL_SUBJECT)
            ->setSubjectTranslationParameters([static::PARAMETER_NAME => $productName])
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
                    ->setEmail($availabilityNotificationSubscriptionTransfer->getEmailOrFail()),
            );
    }
}
