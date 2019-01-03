<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Communication\Plugin;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\Mail\AvailabilityNotificationSubscribedMailTypePlugin;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;

class AvailabilityNotificationSender implements AvailabilityNotificationSenderInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface
     */
    protected $mailFacade;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface $mailFacade
     */
    public function __construct(AvailabilityNotificationToMailFacadeInterface $mailFacade)
    {
        $this->mailFacade = $mailFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return void
     */
    public function sendSubscribedMail(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): void
    {
        $mailTransfer = (new MailTransfer())
            ->setType(AvailabilityNotificationSubscribedMailTypePlugin::MAIL_TYPE)
            ->setAvailabilitySubscription($availabilitySubscriptionTransfer)
            ->setLocale($availabilitySubscriptionTransfer->getLocale());

        $this->mailFacade->handleMail($mailTransfer);
    }
}
