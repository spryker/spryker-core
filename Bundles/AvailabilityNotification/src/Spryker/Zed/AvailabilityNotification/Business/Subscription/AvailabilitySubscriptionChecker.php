<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionExistenceTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\StoreTransfer;
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

        $existingSubscription = $this->repository
            ->findOneByEmailAndSkuAndStore(
                $availabilitySubscriptionTransfer->getEmail(),
                $availabilitySubscriptionTransfer->getSku(),
                $store
            );

        $isExists = $existingSubscription !== null;

        return $this->createAvailabilitySubscriptionExistenceTransfer(
            $availabilitySubscriptionTransfer->getSku(),
            $availabilitySubscriptionTransfer->getEmail(),
            $store,
            $isExists
        );
    }

    /**
     * @param string $sku
     * @param string $email
     * @param \Generated\Shared\Transfer\StoreTransfer $store
     * @param bool $isExists
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceTransfer
     */
    protected function createAvailabilitySubscriptionExistenceTransfer(
        string $sku,
        string $email,
        StoreTransfer $store,
        bool $isExists
    ): AvailabilitySubscriptionExistenceTransfer {
        return (new AvailabilitySubscriptionExistenceTransfer())
            ->setSku($sku)
            ->setEmail($email)
            ->setStore($store)
            ->setIsExists($isExists);
    }
}
