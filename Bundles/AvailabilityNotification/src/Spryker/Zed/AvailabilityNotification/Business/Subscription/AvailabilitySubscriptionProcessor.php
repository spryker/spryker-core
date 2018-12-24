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
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface;

class AvailabilitySubscriptionProcessor implements AvailabilitySubscriptionProcessorInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionExistingCheckerInterface
     */
    protected $availabilitySubscriptionExistingChecker;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface
     */
    protected $mailFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface
     */
    protected $utilValidateService;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionKeyGeneratorInterface
     */
    protected $keyGenerator;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface
     */
    protected $availabilityNotificationToStoreFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface
     */
    protected $availabilityNotificationToLocaleFacade;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface $entityManager
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionExistingCheckerInterface $availabilitySubscriptionExistingChecker
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface $mailFacade
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface $utilValidateService
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionKeyGeneratorInterface $keyGenerator
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface $availabilityNotificationToStoreFacade
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface $availabilityNotificationToLocaleFacade
     */
    public function __construct(
        AvailabilityNotificationEntityManagerInterface $entityManager,
        AvailabilitySubscriptionExistingCheckerInterface $availabilitySubscriptionExistingChecker,
        AvailabilityNotificationToMailFacadeInterface $mailFacade,
        AvailabilityNotificationToUtilValidateServiceInterface $utilValidateService,
        AvailabilitySubscriptionKeyGeneratorInterface $keyGenerator,
        AvailabilityNotificationToStoreFacadeInterface $availabilityNotificationToStoreFacade,
        AvailabilityNotificationToLocaleFacadeInterface $availabilityNotificationToLocaleFacade
    ) {
        $this->entityManager = $entityManager;
        $this->availabilitySubscriptionExistingChecker = $availabilitySubscriptionExistingChecker;
        $this->mailFacade = $mailFacade;
        $this->utilValidateService = $utilValidateService;
        $this->keyGenerator = $keyGenerator;
        $this->availabilityNotificationToStoreFacade = $availabilityNotificationToStoreFacade;
        $this->availabilityNotificationToLocaleFacade = $availabilityNotificationToLocaleFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function process(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        $subscriptionResponse = $this->processSubscription($availabilitySubscriptionTransfer);

        if ($subscriptionResponse->getIsSuccess()) {
            $this->sendSubscribedMail($availabilitySubscriptionTransfer);
        }

        return $subscriptionResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    protected function processSubscription(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        $availabilitySubscriptionTransfer->requireEmail();
        $availabilitySubscriptionTransfer->requireSku();

        $isEmailValid = $this->utilValidateService->isEmailFormatValid($availabilitySubscriptionTransfer->getEmail());

        if (!$isEmailValid) {
            return $this->createInvalidEmailResponse();
        }

        $isAlreadySubscribed = $this->availabilitySubscriptionExistingChecker->check($availabilitySubscriptionTransfer);

        if ($isAlreadySubscribed->getIsSuccess()) {
            return $this->createSubscriptionResponseTransfer(true);
        }

        $subscriptionKey = $this->keyGenerator->generateKey();
        $availabilitySubscriptionTransfer->setSubscriptionKey($subscriptionKey);

        $store = $this->availabilityNotificationToStoreFacade->getCurrentStore();
        $availabilitySubscriptionTransfer->setStore($store);

        $locale = $this->availabilityNotificationToLocaleFacade->getCurrentLocale();
        $availabilitySubscriptionTransfer->setLocale($locale);

        $this->entityManager->saveAvailabilitySubscriptionFromTransfer($availabilitySubscriptionTransfer);

        return $this->createSubscriptionResponseTransfer(true);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return void
     */
    protected function sendSubscribedMail(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): void
    {
        $mailTransfer = (new MailTransfer())
            ->setType(AvailabilityNotificationSubscribedMailTypePlugin::MAIL_TYPE)
            ->setAvailabilitySubscription($availabilitySubscriptionTransfer)
            ->setLocale($availabilitySubscriptionTransfer->getLocale());

        $this->mailFacade->handleMail($mailTransfer);
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
