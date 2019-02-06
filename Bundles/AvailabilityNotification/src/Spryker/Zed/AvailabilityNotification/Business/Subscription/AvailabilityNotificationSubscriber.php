<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Spryker\Shared\Customer\Code\Messages;
use Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationSubscriptionSenderInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface;

class AvailabilityNotificationSubscriber implements AvailabilityNotificationSubscriberInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionSaverInterface
     */
    protected $availabilityNotificationSubscriptionSaver;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationSubscriptionSenderInterface
     */
    protected $availabilityNotificationSubscriptionSender;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface
     */
    protected $utilValidateService;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionReaderInterface
     */
    protected $availabilityNotificationSubscriptionReader;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionSaverInterface $availabilityNotificationSubscriptionSaver
     * @param \Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationSubscriptionSenderInterface $availabilityNotificationSubscriptionSender
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface $utilValidateService
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionReaderInterface $availabilityNotificationSubscriptionReader
     */
    public function __construct(
        AvailabilityNotificationSubscriptionSaverInterface $availabilityNotificationSubscriptionSaver,
        AvailabilityNotificationSubscriptionSenderInterface $availabilityNotificationSubscriptionSender,
        AvailabilityNotificationToUtilValidateServiceInterface $utilValidateService,
        AvailabilityNotificationSubscriptionReaderInterface $availabilityNotificationSubscriptionReader
    ) {
        $this->availabilityNotificationSubscriptionSaver = $availabilityNotificationSubscriptionSaver;
        $this->availabilityNotificationSubscriptionSender = $availabilityNotificationSubscriptionSender;
        $this->utilValidateService = $utilValidateService;
        $this->availabilityNotificationSubscriptionReader = $availabilityNotificationSubscriptionReader;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function subscribe(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer
    {
        $availabilityNotificationSubscriptionTransfer->requireEmail();
        $availabilityNotificationSubscriptionTransfer->requireSku();

        $isEmailValid = $this->utilValidateService->isEmailFormatValid($availabilityNotificationSubscriptionTransfer->getEmail());

        if ($isEmailValid === false) {
            return $this->createInvalidEmailResponse();
        }

        $existingAvailabilityNotificationSubscriptionTransfer = $this->availabilityNotificationSubscriptionReader
            ->findOneByEmailAndSku($availabilityNotificationSubscriptionTransfer->getEmail(), $availabilityNotificationSubscriptionTransfer->getSku());

        if ($existingAvailabilityNotificationSubscriptionTransfer !== null) {
            return $this->createSubscriptionAlreadyExistsResponse();
        }

        $availabilityNotificationSubscriptionTransfer = $this->availabilityNotificationSubscriptionSaver->save($availabilityNotificationSubscriptionTransfer);

        $this->availabilityNotificationSubscriptionSender->send($availabilityNotificationSubscriptionTransfer);

        return $this->createSubscriptionResponseTransfer(true)
            ->setAvailabilityNotificationSubscription($availabilityNotificationSubscriptionTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    protected function createInvalidEmailResponse(): AvailabilityNotificationSubscriptionResponseTransfer
    {
        return $this->createSubscriptionResponseTransfer(false)
            ->setErrorMessage(Messages::CUSTOMER_EMAIL_FORMAT_INVALID);
    }

    /**
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    protected function createSubscriptionAlreadyExistsResponse(): AvailabilityNotificationSubscriptionResponseTransfer
    {
        return $this->createSubscriptionResponseTransfer(false)
            ->setErrorMessage('Subscription already exists');
    }

    /**
     * @param bool $isSuccess
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    protected function createSubscriptionResponseTransfer(bool $isSuccess): AvailabilityNotificationSubscriptionResponseTransfer
    {
        return (new AvailabilityNotificationSubscriptionResponseTransfer())->setIsSuccess($isSuccess);
    }
}
