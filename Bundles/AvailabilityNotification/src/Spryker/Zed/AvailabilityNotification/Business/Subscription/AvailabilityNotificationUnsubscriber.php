<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationSenderInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface;

class AvailabilityNotificationUnsubscriber implements AvailabilityNotificationUnsubscriberInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationSenderInterface
     */
    protected $availabilityNotificationSender;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionReaderInterface
     */
    protected $availabilityNotificationReader;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface $entityManager
     * @param \Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationSenderInterface $availabilityNotificationSender
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionReaderInterface $availabilityNotificationReader
     */
    public function __construct(
        AvailabilityNotificationEntityManagerInterface $entityManager,
        AvailabilityNotificationSenderInterface $availabilityNotificationSender,
        AvailabilitySubscriptionReaderInterface $availabilityNotificationReader
    ) {
        $this->entityManager = $entityManager;
        $this->availabilityNotificationSender = $availabilityNotificationSender;
        $this->availabilityNotificationReader = $availabilityNotificationReader;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function unsubscribeBySubscriptionKey(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        $availabilitySubscriptionTransfer->requireSubscriptionKey();

        $availabilitySubscriptionTransfer = $this->availabilityNotificationReader->findOneBySubscriptionKey($availabilitySubscriptionTransfer->getSubscriptionKey());

        if ($availabilitySubscriptionTransfer === null) {
            return $this->createSubscriptionNotExistsResponse();
        }

        $this->unsubscribe($availabilitySubscriptionTransfer);

        return $this->createSuccessResponse($availabilitySubscriptionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function unsubscribeByCustomerReferenceAndSku(
        AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
    ): AvailabilitySubscriptionResponseTransfer {
        $availabilitySubscriptionTransfer->requireCustomerReference();
        $availabilitySubscriptionTransfer->requireSku();

        $availabilitySubscriptionTransfer = $this->availabilityNotificationReader->findOneByCustomerReferenceAndSku(
            $availabilitySubscriptionTransfer->getCustomerReference(),
            $availabilitySubscriptionTransfer->getSku()
        );

        if ($availabilitySubscriptionTransfer === null) {
            return $this->createSubscriptionNotExistsResponse();
        }

        $this->unsubscribe($availabilitySubscriptionTransfer);

        return $this->createSuccessResponse($availabilitySubscriptionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return void
     */
    protected function unsubscribe(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): void
    {
        $this->entityManager->deleteBySubscriptionKey($availabilitySubscriptionTransfer->getSubscriptionKey());
        $this->availabilityNotificationSender->sendUnsubscriptionMail($availabilitySubscriptionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    protected function createSuccessResponse(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        return (new AvailabilitySubscriptionResponseTransfer())
            ->setIsSuccess(true)
            ->setAvailabilitySubscription($availabilitySubscriptionTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    protected function createSubscriptionNotExistsResponse(): AvailabilitySubscriptionResponseTransfer
    {
        return (new AvailabilitySubscriptionResponseTransfer())
            ->setIsSuccess(false)
            ->setErrorMessage('Subscription doesn\'t exist');
    }
}
