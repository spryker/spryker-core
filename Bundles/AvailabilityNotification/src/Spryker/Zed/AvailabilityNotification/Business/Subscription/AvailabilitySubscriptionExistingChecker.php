<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface;

class AvailabilitySubscriptionExistingChecker implements AvailabilitySubscriptionExistingCheckerInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface
     */
    protected $availabilityNotificationToStoreClient;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface $availabilityNotificationToStoreClient
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface $repository
     */
    public function __construct(
        AvailabilityNotificationToStoreFacadeInterface $availabilityNotificationToStoreClient,
        AvailabilityNotificationRepositoryInterface $repository
    ) {
        $this->availabilityNotificationToStoreClient = $availabilityNotificationToStoreClient;
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function check(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        $availabilityNotificationSubscriptionTransfer->requireEmail();
        $availabilityNotificationSubscriptionTransfer->requireSku();
        $store = $availabilityNotificationSubscriptionTransfer->getStore();

        if ($store === null) {
            $store = $this->availabilityNotificationToStoreClient->getCurrentStore();
        }

        $existingSubscription = $this->repository
            ->findOneByEmailAndSkuAndStore(
                $availabilityNotificationSubscriptionTransfer->getEmail(),
                $availabilityNotificationSubscriptionTransfer->getSku(),
                $store
            );

        return (new AvailabilitySubscriptionResponseTransfer())->setIsSuccess($existingSubscription !== null);
    }
}
