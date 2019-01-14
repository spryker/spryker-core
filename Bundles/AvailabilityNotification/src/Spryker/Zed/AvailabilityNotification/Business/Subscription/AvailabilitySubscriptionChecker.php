<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionExistenceRequestTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionExistenceResponseTransfer;
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
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceResponseTransfer
     */
    public function checkExistence(
        AvailabilitySubscriptionExistenceRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
    ): AvailabilitySubscriptionExistenceResponseTransfer {
        if ($availabilitySubscriptionExistenceRequestTransfer->getSubscriptionKey() !== null) {
            return $this->findBySubscriptionKey($availabilitySubscriptionExistenceRequestTransfer);
        }

        return $this->findByEmailAndSku($availabilitySubscriptionExistenceRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceResponseTransfer
     */
    protected function findBySubscriptionKey(
        AvailabilitySubscriptionExistenceRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
    ): AvailabilitySubscriptionExistenceResponseTransfer {
        $availabilitySubscriptionExistenceRequestTransfer->requireSubscriptionKey();

        $availabilitySubscription = $this->availabilityNotificationRepository
            ->findOneBySubscriptionKey($availabilitySubscriptionExistenceRequestTransfer->getSubscriptionKey());

        return (new AvailabilitySubscriptionExistenceResponseTransfer())
            ->setAvailabilitySubscription($availabilitySubscription);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceResponseTransfer
     */
    protected function findByEmailAndSku(
        AvailabilitySubscriptionExistenceRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
    ): AvailabilitySubscriptionExistenceResponseTransfer {
        $availabilitySubscriptionExistenceRequestTransfer->requireEmail();
        $availabilitySubscriptionExistenceRequestTransfer->requireSku();

        $store = $this->availabilityNotificationToStoreClient->getCurrentStore();

        $availabilitySubscriptionTransfer = $this->availabilityNotificationRepository
            ->findOneBy(
                $availabilitySubscriptionExistenceRequestTransfer->getEmail(),
                $availabilitySubscriptionExistenceRequestTransfer->getSku(),
                $store
            );

        return (new AvailabilitySubscriptionExistenceResponseTransfer())
            ->setAvailabilitySubscription($availabilitySubscriptionTransfer);
    }
}
