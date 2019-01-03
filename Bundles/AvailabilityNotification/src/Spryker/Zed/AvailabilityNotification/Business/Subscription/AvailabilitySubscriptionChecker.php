<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionExistenceTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface;

class AvailabilitySubscriptionChecker implements AvailabilitySubscriptionCheckerInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface
     */
    protected $availabilityNotificationToStoreClient;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface
     */
    protected $availabilityNotificationRepository;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface $availabilityNotificationToStoreClient
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface $availabilityNotificationRepository
     */
    public function __construct(
        AvailabilityNotificationToStoreFacadeInterface $availabilityNotificationToStoreClient,
        AvailabilityNotificationRepositoryInterface $availabilityNotificationRepository
    ) {
        $this->availabilityNotificationToStoreClient = $availabilityNotificationToStoreClient;
        $this->availabilityNotificationRepository = $availabilityNotificationRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceTransfer
     */
    public function checkExistence(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionExistenceTransfer
    {
        $availabilitySubscriptionTransfer->requireEmail();
        $availabilitySubscriptionTransfer->requireSku();
        $store = $availabilitySubscriptionTransfer->getStore();

        if ($store === null) {
            $store = $this->availabilityNotificationToStoreClient->getCurrentStore();
        }

        $existingSubscription = $this->availabilityNotificationRepository
            ->findOneBy(
                $availabilitySubscriptionTransfer->getEmail(),
                $availabilitySubscriptionTransfer->getSku(),
                $store
            );

        $isExists = $existingSubscription !== null;

        return (new AvailabilitySubscriptionExistenceTransfer())
            ->setSku($availabilitySubscriptionTransfer->getSku())
            ->setEmail($availabilitySubscriptionTransfer->getEmail())
            ->setStore($store)
            ->setIsExists($isExists);
    }
}
