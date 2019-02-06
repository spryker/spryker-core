<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationUnsubscriptionSenderInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface;

class AvailabilityNotificationUnsubscriber implements AvailabilityNotificationUnsubscriberInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationUnsubscriptionSenderInterface
     */
    protected $availabilityNotificationUnsubscriptionSender;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionReaderInterface
     */
    protected $availabilityNotificationReader;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface $entityManager
     * @param \Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationUnsubscriptionSenderInterface $availabilityNotificationUnsubscriptionSender
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionReaderInterface $availabilityNotificationReader
     */
    public function __construct(
        AvailabilityNotificationEntityManagerInterface $entityManager,
        AvailabilityNotificationUnsubscriptionSenderInterface $availabilityNotificationUnsubscriptionSender,
        AvailabilityNotificationSubscriptionReaderInterface $availabilityNotificationReader
    ) {
        $this->entityManager = $entityManager;
        $this->availabilityNotificationUnsubscriptionSender = $availabilityNotificationUnsubscriptionSender;
        $this->availabilityNotificationReader = $availabilityNotificationReader;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function unsubscribeBySubscriptionKey(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer
    {
        $availabilityNotificationSubscriptionTransfer->requireSubscriptionKey();

        $availabilityNotificationSubscriptionTransfer = $this->availabilityNotificationReader->findOneBySubscriptionKey($availabilityNotificationSubscriptionTransfer->getSubscriptionKey());

        if ($availabilityNotificationSubscriptionTransfer === null) {
            return $this->createSubscriptionNotExistsResponse();
        }

        $this->unsubscribe($availabilityNotificationSubscriptionTransfer);

        return $this->createSuccessResponse($availabilityNotificationSubscriptionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function unsubscribeByCustomerReferenceAndSku(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): AvailabilityNotificationSubscriptionResponseTransfer {
        $availabilityNotificationSubscriptionTransfer->requireCustomerReference();
        $availabilityNotificationSubscriptionTransfer->requireSku();

        $availabilityNotificationSubscriptionTransfer = $this->availabilityNotificationReader->findOneByCustomerReferenceAndSku(
            $availabilityNotificationSubscriptionTransfer->getCustomerReference(),
            $availabilityNotificationSubscriptionTransfer->getSku()
        );

        if ($availabilityNotificationSubscriptionTransfer === null) {
            return $this->createSubscriptionNotExistsResponse();
        }

        $this->unsubscribe($availabilityNotificationSubscriptionTransfer);

        return $this->createSuccessResponse($availabilityNotificationSubscriptionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return void
     */
    protected function unsubscribe(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): void
    {
        $this->entityManager->deleteBySubscriptionKey($availabilityNotificationSubscriptionTransfer->getSubscriptionKey());
        $this->availabilityNotificationUnsubscriptionSender->send($availabilityNotificationSubscriptionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    protected function createSuccessResponse(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer
    {
        return (new AvailabilityNotificationSubscriptionResponseTransfer())
            ->setIsSuccess(true)
            ->setAvailabilityNotificationSubscription($availabilityNotificationSubscriptionTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    protected function createSubscriptionNotExistsResponse(): AvailabilityNotificationSubscriptionResponseTransfer
    {
        return (new AvailabilityNotificationSubscriptionResponseTransfer())
            ->setIsSuccess(false)
            ->setErrorMessage('Subscription doesn\'t exist');
    }
}
