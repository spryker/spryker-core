<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Exception;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Shared\AvailabilityNotification\Messages\Messages;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\Mail\AvailabilityNotificationSubscribedMailTypePlugin;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationQueryContainerInterface;

class SubscriptionHandler implements SubscriptionHandlerInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\SubscriptionManagerInterface
     */
    protected $subscriptionManager;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailInterface
     */
    protected $mailFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface
     */
    protected $utilValidateService;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\SubscriptionManagerInterface $subscriptionManager
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailInterface $mailFacade
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface $utilValidateService
     */
    public function __construct(
        SubscriptionManagerInterface $subscriptionManager,
        AvailabilityNotificationQueryContainerInterface $queryContainer,
        AvailabilityNotificationToMailInterface $mailFacade,
        AvailabilityNotificationToUtilValidateServiceInterface $utilValidateService
    ) {
        $this->subscriptionManager = $subscriptionManager;
        $this->queryContainer = $queryContainer;
        $this->mailFacade = $mailFacade;
        $this->utilValidateService = $utilValidateService;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function processAvailabilityNotificationSubscription(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer
    {
        $connection = $this->queryContainer->getConnection();
        $connection->beginTransaction();

        try {
            $subscriptionResponse = $this->processSubscription($availabilityNotificationSubscriptionTransfer);

            if ($subscriptionResponse->getIsSuccess()) {
                $this->sendSubscribedMail($availabilityNotificationSubscriptionTransfer);
            }

            $connection->commit();
        } catch (Exception $e) {
            $connection->rollBack();

            throw $e;
        }

        return $subscriptionResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function processAvailabilityNotificationUnsubscription(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer
    {
        $connection = $this->queryContainer->getConnection();
        $connection->beginTransaction();

        try {
            $isSuccess = $this->subscriptionManager->unsubscribe($availabilityNotificationSubscriptionTransfer);
            $subscriptionResponse = $this->createSubscriptionResponseTransfer($isSuccess);

            $connection->commit();
        } catch (Exception $e) {
            $connection->rollBack();

            throw $e;
        }

        return $subscriptionResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return void
     */
    protected function sendSubscribedMail(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): void {
        $mailTransfer = (new MailTransfer())
            ->setType(AvailabilityNotificationSubscribedMailTypePlugin::MAIL_TYPE)
            ->setAvailabilityNotificationSubscription($availabilityNotificationSubscriptionTransfer)
            ->setLocale($availabilityNotificationSubscriptionTransfer->getLocale());

        $this->mailFacade->handleMail($mailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function checkAvailabilityNotificationSubscription(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer {
        $isAlreadySubscribed = $this->subscriptionManager->isAlreadySubscribed($availabilityNotificationSubscriptionTransfer);

        return $this->createSubscriptionResponseTransfer($isAlreadySubscribed);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer
     */
    protected function getAvailabilityNotificationSubscription(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionTransfer {
        $availabilityNotificationSubscriptionTransfer->requireEmail();
        $loadedAvailabilityNotificationSubscriptionTransfer = $this->subscriptionManager->findSubscriptionByEmail($availabilityNotificationSubscriptionTransfer->getEmail());

        if ($loadedAvailabilityNotificationSubscriptionTransfer === null) {
            $loadedAvailabilityNotificationSubscriptionTransfer = $this->subscriptionManager->createSubscriptionFromTransfer($availabilityNotificationSubscriptionTransfer);
        }

        return $loadedAvailabilityNotificationSubscriptionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    protected function processSubscription(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer {
        $isEmailValid = $this->utilValidateService->isEmailFormatValid($availabilityNotificationSubscriptionTransfer->getEmail());

        if (!$isEmailValid) {
            return $this->createInvalidEmailResponse();
        }

        $isAlreadySubscribed = $this->subscriptionManager->isAlreadySubscribed($availabilityNotificationSubscriptionTransfer);

        if ($isAlreadySubscribed) {
            return $this->createAlreadySubscribedResponse();
        }

        $this->subscriptionManager->subscribe($availabilityNotificationSubscriptionTransfer);

        return $this->createSubscriptionResponseTransfer(true);
    }

    /**
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    protected function createSubscriptionResponse(): AvailabilityNotificationSubscriptionResponseTransfer
    {
        return new AvailabilityNotificationSubscriptionResponseTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    protected function createAlreadySubscribedResponse(): AvailabilityNotificationSubscriptionResponseTransfer
    {
        return $this->createSubscriptionResponseTransfer(false)
            ->setErrorMessage(Messages::ALREADY_SUBSCRIBED);
    }

    /**
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    protected function createInvalidEmailResponse(): AvailabilityNotificationSubscriptionResponseTransfer
    {
        return $this->createSubscriptionResponseTransfer(false)
            ->setErrorMessage(Messages::INVALID_EMAIL_FORMAT);
    }

    /**
     * @param bool $isSuccess
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    protected function createSubscriptionResponseTransfer($isSuccess): AvailabilityNotificationSubscriptionResponseTransfer
    {
        return (new AvailabilityNotificationSubscriptionResponseTransfer())->setIsSuccess($isSuccess);
    }
}
