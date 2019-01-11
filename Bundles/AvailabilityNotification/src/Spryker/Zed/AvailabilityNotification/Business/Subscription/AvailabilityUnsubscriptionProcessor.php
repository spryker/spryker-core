<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\AvailabilityNotificationSenderInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface;

class AvailabilityUnsubscriptionProcessor implements AvailabilityUnsubscriptionProcessorInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Communication\Plugin\AvailabilityNotificationSenderInterface
     */
    protected $availabilityNotificationSender;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface $entityManager
     * @param \Spryker\Zed\AvailabilityNotification\Communication\Plugin\AvailabilityNotificationSenderInterface $availabilityNotificationSender
     */
    public function __construct(AvailabilityNotificationEntityManagerInterface $entityManager, AvailabilityNotificationSenderInterface $availabilityNotificationSender)
    {
        $this->entityManager = $entityManager;
        $this->availabilityNotificationSender = $availabilityNotificationSender;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function process(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        $availabilitySubscriptionTransfer->requireSubscriptionKey();

        $this->entityManager->deleteBySubscriptionKey($availabilitySubscriptionTransfer->getSubscriptionKey());
        $this->availabilityNotificationSender->sendUnsubscribedMail($availabilitySubscriptionTransfer);

        return (new AvailabilitySubscriptionResponseTransfer())->setIsSuccess(true);
    }
}
