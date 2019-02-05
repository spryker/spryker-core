<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationSenderInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface;

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
     * @var \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface
     */
    protected $availabilityNotificationRepository;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface $entityManager
     * @param \Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationSenderInterface $availabilityNotificationSender
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface $availabilityNotificationRepository
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface $productFacade
     */
    public function __construct(
        AvailabilityNotificationEntityManagerInterface $entityManager,
        AvailabilityNotificationSenderInterface $availabilityNotificationSender,
        AvailabilityNotificationRepositoryInterface $availabilityNotificationRepository,
        AvailabilityNotificationToProductFacadeInterface $productFacade
    ) {
        $this->entityManager = $entityManager;
        $this->availabilityNotificationSender = $availabilityNotificationSender;
        $this->availabilityNotificationRepository = $availabilityNotificationRepository;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function unsubscribe(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        $availabilitySubscriptionTransfer->requireSubscriptionKey();

        $availabilitySubscriptionTransfer = $this->availabilityNotificationRepository->findOneBySubscriptionKey($availabilitySubscriptionTransfer->getSubscriptionKey());

        if ($availabilitySubscriptionTransfer === null) {
            return (new AvailabilitySubscriptionResponseTransfer())
                ->setIsSuccess(false)
                ->setErrorMessage('Subscription doesn\'t exist');
        }

        $this->entityManager->deleteBySubscriptionKey($availabilitySubscriptionTransfer->getSubscriptionKey());
        $this->availabilityNotificationSender->sendUnsubscriptionMail($availabilitySubscriptionTransfer);

        return (new AvailabilitySubscriptionResponseTransfer())
            ->setIsSuccess(true)
            ->setAvailabilitySubscription($availabilitySubscriptionTransfer);
    }
}
