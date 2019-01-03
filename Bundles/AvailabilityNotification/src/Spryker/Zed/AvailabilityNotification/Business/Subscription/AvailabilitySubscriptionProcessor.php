<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Spryker\Shared\Customer\Code\Messages;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\AvailabilityNotificationSenderInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface;
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
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionCheckerInterface
     */
    protected $availabilitySubscriptionExistingChecker;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Communication\Plugin\AvailabilityNotificationSenderInterface
     */
    protected $sender;

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
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionCheckerInterface $availabilitySubscriptionExistingChecker
     * @param \Spryker\Zed\AvailabilityNotification\Communication\Plugin\AvailabilityNotificationSenderInterface $sender
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface $utilValidateService
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionKeyGeneratorInterface $keyGenerator
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface $availabilityNotificationToStoreFacade
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface $availabilityNotificationToLocaleFacade
     */
    public function __construct(
        AvailabilityNotificationEntityManagerInterface $entityManager,
        AvailabilitySubscriptionCheckerInterface $availabilitySubscriptionExistingChecker,
        AvailabilityNotificationSenderInterface $sender,
        AvailabilityNotificationToUtilValidateServiceInterface $utilValidateService,
        AvailabilitySubscriptionKeyGeneratorInterface $keyGenerator,
        AvailabilityNotificationToStoreFacadeInterface $availabilityNotificationToStoreFacade,
        AvailabilityNotificationToLocaleFacadeInterface $availabilityNotificationToLocaleFacade
    ) {
        $this->entityManager = $entityManager;
        $this->availabilitySubscriptionExistingChecker = $availabilitySubscriptionExistingChecker;
        $this->sender = $sender;
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
        $this->assertAvailabilitySubscriptionTransfer($availabilitySubscriptionTransfer);

        $isEmailValid = $this->utilValidateService->isEmailFormatValid($availabilitySubscriptionTransfer->getEmail());

        if (!$isEmailValid) {
            return $this->createInvalidEmailResponse();
        }

        $availabilitySubscriptionExistenceTransfer = $this->availabilitySubscriptionExistingChecker->checkExistence($availabilitySubscriptionTransfer);

        if ($availabilitySubscriptionExistenceTransfer->getIsExists()) {
            return $this->createSubscriptionResponseTransfer(true);
        }

        $this->saveAvailabilitySubscription($availabilitySubscriptionTransfer);

        $this->sender->sendSubscribedMail($availabilitySubscriptionTransfer);

        return $this->createSubscriptionResponseTransfer(true);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return void
     */
    protected function assertAvailabilitySubscriptionTransfer(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): void
    {
        $availabilitySubscriptionTransfer->requireEmail();
        $availabilitySubscriptionTransfer->requireSku();
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return void
     */
    protected function saveAvailabilitySubscription(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): void
    {
        $subscriptionKey = $this->keyGenerator->generateKey();
        $availabilitySubscriptionTransfer->setSubscriptionKey($subscriptionKey);

        $store = $this->availabilityNotificationToStoreFacade->getCurrentStore();
        $availabilitySubscriptionTransfer->setStore($store);

        $locale = $this->availabilityNotificationToLocaleFacade->getCurrentLocale();
        $availabilitySubscriptionTransfer->setLocale($locale);

        $this->entityManager->saveAvailabilitySubscription($availabilitySubscriptionTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    protected function createInvalidEmailResponse(): AvailabilitySubscriptionResponseTransfer
    {
        return $this->createSubscriptionResponseTransfer(false)
            ->setErrorMessage(Messages::CUSTOMER_EMAIL_FORMAT_INVALID);
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
