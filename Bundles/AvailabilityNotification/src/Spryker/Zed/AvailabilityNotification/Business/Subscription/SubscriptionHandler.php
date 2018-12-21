<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Shared\AvailabilityNotification\Messages\Messages;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\Mail\AvailabilityNotificationSubscribedMailTypePlugin;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface;

class SubscriptionHandler implements SubscriptionHandlerInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\SubscriptionManagerInterface
     */
    protected $subscriptionManager;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface
     */
    protected $mailFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface
     */
    protected $utilValidateService;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\SubscriptionManagerInterface $subscriptionManager
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface $repository
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface $mailFacade
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface $utilValidateService
     */
    public function __construct(
        SubscriptionManagerInterface $subscriptionManager,
        AvailabilityNotificationRepositoryInterface $repository,
        AvailabilityNotificationToMailFacadeInterface $mailFacade,
        AvailabilityNotificationToUtilValidateServiceInterface $utilValidateService
    ) {
        $this->subscriptionManager = $subscriptionManager;
        $this->repository = $repository;
        $this->mailFacade = $mailFacade;
        $this->utilValidateService = $utilValidateService;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @throws \Throwable
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function processAvailabilitySubscription(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        $subscriptionResponse = $this->processSubscription($availabilityNotificationSubscriptionTransfer);

        if ($subscriptionResponse->getIsSuccess()) {
            $this->sendSubscribedMail($availabilityNotificationSubscriptionTransfer);
        }

        return $subscriptionResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @throws \Throwable
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function processAvailabilityNotificationUnsubscription(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        $isSuccess = $this->subscriptionManager->unsubscribe($availabilityNotificationSubscriptionTransfer);

        return $this->createSubscriptionResponseTransfer($isSuccess);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return void
     */
    protected function sendSubscribedMail(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer): void
    {
        $mailTransfer = (new MailTransfer())
            ->setType(AvailabilityNotificationSubscribedMailTypePlugin::MAIL_TYPE)
            ->setAvailabilitySubscription($availabilityNotificationSubscriptionTransfer)
            ->setLocale($availabilityNotificationSubscriptionTransfer->getLocale());

        $this->mailFacade->handleMail($mailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function checkAvailabilitySubscription(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        $isAlreadySubscribed = $this->subscriptionManager->isAlreadySubscribed($availabilityNotificationSubscriptionTransfer);

        return $this->createSubscriptionResponseTransfer($isAlreadySubscribed);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    protected function processSubscription(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        $isEmailValid = $this->utilValidateService->isEmailFormatValid($availabilityNotificationSubscriptionTransfer->getEmail());

        if (!$isEmailValid) {
            return $this->createInvalidEmailResponse();
        }

        $isAlreadySubscribed = $this->subscriptionManager->isAlreadySubscribed($availabilityNotificationSubscriptionTransfer);

        if ($isAlreadySubscribed) {
            return $this->createSubscriptionResponseTransfer(true);
        }

        $this->subscriptionManager->subscribe($availabilityNotificationSubscriptionTransfer);

        return $this->createSubscriptionResponseTransfer(true);
    }

    /**
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    protected function createInvalidEmailResponse(): AvailabilitySubscriptionResponseTransfer
    {
        return $this->createSubscriptionResponseTransfer(false)
            ->setErrorMessage(Messages::INVALID_EMAIL_FORMAT);
    }

    /**
     * @param bool $isSuccess
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    protected function createSubscriptionResponseTransfer(bool $isSuccess): AvailabilitySubscriptionResponseTransfer
    {
        return (new AvailabilitySubscriptionResponseTransfer())->setIsSuccess($isSuccess);
    }
}
