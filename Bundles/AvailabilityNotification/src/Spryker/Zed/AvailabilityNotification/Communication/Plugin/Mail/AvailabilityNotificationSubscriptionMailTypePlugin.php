<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Communication\Plugin\Mail;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface;
use Spryker\Zed\Mail\Dependency\Plugin\MailTypePluginInterface;

/**
 * @method \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacadeInterface getFacade()
 * @method \Spryker\Zed\AvailabilityNotification\Communication\AvailabilityNotificationCommunicationFactory getFactory()
 * @method \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig getConfig()
 */
class AvailabilityNotificationSubscriptionMailTypePlugin extends AbstractPlugin implements MailTypePluginInterface
{
    public const AVAILABILITY_NOTIFICATION_SUBSCRIPTION_MAIL = 'AVAILABILITY_NOTIFICATION_SUBSCRIPTION_MAIL';

    /**
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::AVAILABILITY_NOTIFICATION_SUBSCRIPTION_MAIL;
    }

    /**
     * @api
     *
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return void
     */
    public function build(MailBuilderInterface $mailBuilder): void
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
     * @return $this
     */
    protected function setSubject(MailBuilderInterface $mailBuilder)
    {
        $mailBuilder->setSubject('availability_notification_subscription.mail.subscribed.subject');

        return $this;
    }

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return $this
     */
    protected function setHtmlTemplate(MailBuilderInterface $mailBuilder)
    {
        $mailBuilder->setHtmlTemplate('AvailabilityNotification/mail/subscribed.html.twig');

        return $this;
    }

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return $this
     */
    protected function setTextTemplate(MailBuilderInterface $mailBuilder)
    {
        $mailBuilder->setTextTemplate('AvailabilityNotification/mail/subscribed.text.twig');

        return $this;
    }

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return $this
     */
    protected function setRecipient(MailBuilderInterface $mailBuilder)
    {
        $mailTransfer = $mailBuilder->getMailTransfer();
        $mailTransfer->requireAvailabilityNotificationSubscriptionMailData();
        $availabilityNotificationSubscriptionTransfer = $mailTransfer->getAvailabilityNotificationSubscriptionMailData()->getAvailabilityNotificationSubscription();

        $mailBuilder->addRecipient($availabilityNotificationSubscriptionTransfer->getEmail(), '');

        return $this;
    }

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return $this
     */
    protected function setSender(MailBuilderInterface $mailBuilder)
    {
        $mailBuilder->setSender('mail.sender.email', 'mail.sender.name');

        return $this;
    }
}
