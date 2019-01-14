<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Spryker\Shared\Customer\Code\Messages;
use Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface;

class AvailabilitySubscriptionProcessor implements AvailabilitySubscriptionProcessorInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionSaverInterface
     */
    protected $availabilitySubscriptionSaver;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSenderInterface
     */
    protected $availabilityNotificationSender;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface
     */
    protected $utilValidateService;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionSaverInterface $availabilitySubscriptionSaver
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSenderInterface $availabilityNotificationSender
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface $utilValidateService
     */
    public function __construct(
        AvailabilitySubscriptionSaverInterface $availabilitySubscriptionSaver,
        AvailabilityNotificationSenderInterface $availabilityNotificationSender,
        AvailabilityNotificationToUtilValidateServiceInterface $utilValidateService
    ) {
        $this->availabilitySubscriptionSaver = $availabilitySubscriptionSaver;
        $this->availabilityNotificationSender = $availabilityNotificationSender;
        $this->utilValidateService = $utilValidateService;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function process(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        $availabilitySubscriptionTransfer->requireEmail();
        $availabilitySubscriptionTransfer->requireSku();

        $isEmailValid = $this->utilValidateService->isEmailFormatValid($availabilitySubscriptionTransfer->getEmail());

        if ($isEmailValid === false) {
            return $this->createInvalidEmailResponse();
        }

        $availabilitySubscriptionTransfer = $this->availabilitySubscriptionSaver->save($availabilitySubscriptionTransfer);

        $this->availabilityNotificationSender->sendSubscriptionMail($availabilitySubscriptionTransfer);

        return $this->createSubscriptionResponseTransfer(true);
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
