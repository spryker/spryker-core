<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscription;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface;

class SubscriptionManager implements SubscriptionManagerInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\SubscriptionKeyGeneratorInterface
     */
    protected $subscriptionKeyGenerator;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface
     */
    protected $availabilityNotificationToStoreClient;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface
     */
    protected $availabilityNotificationToLocaleFacade;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface $repository
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\SubscriptionKeyGeneratorInterface $subscriptionKeyGenerator
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface $availabilityNotificationToStoreClient
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface $availabilityNotificationToLocaleFacade
     */
    public function __construct(
        AvailabilityNotificationRepositoryInterface $repository,
        SubscriptionKeyGeneratorInterface $subscriptionKeyGenerator,
        AvailabilityNotificationToStoreFacadeInterface $availabilityNotificationToStoreClient,
        AvailabilityNotificationToLocaleFacadeInterface $availabilityNotificationToLocaleFacade
    ) {
        $this->repository = $repository;
        $this->subscriptionKeyGenerator = $subscriptionKeyGenerator;
        $this->availabilityNotificationToStoreClient = $availabilityNotificationToStoreClient;
        $this->availabilityNotificationToLocaleFacade = $availabilityNotificationToLocaleFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return void
     */
    public function subscribe(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer): void
    {
        $availabilityNotificationSubscriptionTransfer->requireEmail();
        $availabilityNotificationSubscriptionTransfer->requireSku();

        $subscriptionEntity = $this->createSubscriptionEntityFromTransfer($availabilityNotificationSubscriptionTransfer);

        $subscriptionEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return bool
     */
    public function isAlreadySubscribed(
        AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): bool {
        $availabilityNotificationSubscriptionTransfer->requireEmail();
        $availabilityNotificationSubscriptionTransfer->requireSku();

        $currentStore = $this->availabilityNotificationToStoreClient->getCurrentStore();

        $subscription = $this->repository
            ->findOneSubscriptionByEmailAndSkuAndStore(
                $availabilityNotificationSubscriptionTransfer->getEmail(),
                $availabilityNotificationSubscriptionTransfer->getSku(),
                $currentStore
            );

        return $subscription !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return bool
     */
    public function unsubscribe(
        AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): bool {
        $subscriptionEntity = $this->findExistingSubscription($availabilityNotificationSubscriptionTransfer);

        if ($subscriptionEntity !== null) {
            $subscriptionEntity->delete();

            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer|null
     */
    protected function findExistingSubscription(
        AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): ?AvailabilitySubscriptionTransfer {
        if ($availabilityNotificationSubscriptionTransfer->getSubscriptionKey() !== null) {
            return $this->repository
                ->findOneSubscriptionBySubscriptionKey($availabilityNotificationSubscriptionTransfer->getSubscriptionKey());
        }

        if ($availabilityNotificationSubscriptionTransfer->getEmail() !== null) {
            return $this->repository
                ->findOneSubscriptionByEmailAndSkuAndStore(
                    $availabilityNotificationSubscriptionTransfer->getEmail(),
                    $availabilityNotificationSubscriptionTransfer->getSku(),
                    $availabilityNotificationSubscriptionTransfer->getStore()
                );
        }

        if ($availabilityNotificationSubscriptionTransfer->getCustomerReference() !== null) {
            return $this->repository
                ->findOneSubscriptionByCustomerReferenceAndSkuAndStore(
                    $availabilityNotificationSubscriptionTransfer->getCustomerReference(),
                    $availabilityNotificationSubscriptionTransfer->getSku(),
                    $availabilityNotificationSubscriptionTransfer->getStore()
                );
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscription
     */
    public function createSubscriptionEntityFromTransfer(
        AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): SpyAvailabilitySubscription {
        $subscriptionEntity = new SpyAvailabilitySubscription();
        $subscriptionEntity->fromArray($availabilityNotificationSubscriptionTransfer->toArray());

        $subscriptionKey = $this->subscriptionKeyGenerator->generateKey();
        $subscriptionEntity->setSubscriptionKey($subscriptionKey);

        $store = $this->availabilityNotificationToStoreClient->getCurrentStore();
        $subscriptionEntity->setFkStore($store->getIdStore());

        $locale = $this->availabilityNotificationToLocaleFacade->getCurrentLocale();
        $subscriptionEntity->setFkLocale($locale->getIdLocale());

        $subscriptionEntity->save();

        return $subscriptionEntity;
    }
}
