<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscription;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationQueryContainerInterface;

class SubscriptionManager implements SubscriptionManagerInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationQueryContainerInterface
     */
    protected $queryContainer;

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
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\SubscriptionKeyGeneratorInterface $subscriptionKeyGenerator
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface $availabilityNotificationToStoreClient
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface $availabilityNotificationToLocaleFacade
     */
    public function __construct(
        AvailabilityNotificationQueryContainerInterface $queryContainer,
        SubscriptionKeyGeneratorInterface $subscriptionKeyGenerator,
        AvailabilityNotificationToStoreFacadeInterface $availabilityNotificationToStoreClient,
        AvailabilityNotificationToLocaleFacadeInterface $availabilityNotificationToLocaleFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->subscriptionKeyGenerator = $subscriptionKeyGenerator;
        $this->availabilityNotificationToStoreClient = $availabilityNotificationToStoreClient;
        $this->availabilityNotificationToLocaleFacade = $availabilityNotificationToLocaleFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return void
     */
    public function subscribe(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): void
    {
        $availabilityNotificationSubscriptionTransfer->requireEmail();
        $availabilityNotificationSubscriptionTransfer->requireSku();

        $subscriptionEntity = $this->createSubscriptionEntityFromTransfer($availabilityNotificationSubscriptionTransfer);

        $subscriptionEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return bool
     */
    public function isAlreadySubscribed(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): bool {
        $availabilityNotificationSubscriptionTransfer->requireEmail();
        $availabilityNotificationSubscriptionTransfer->requireSku();

        $currentStore = $this->availabilityNotificationToStoreClient->getCurrentStore();

        $subscriptionCount = $this->queryContainer
            ->querySubscriptionByEmailAndSkuAndStore(
                $availabilityNotificationSubscriptionTransfer->getEmail(),
                $availabilityNotificationSubscriptionTransfer->getSku(),
                $currentStore
            )
            ->count();

        return $subscriptionCount > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return bool
     */
    public function unsubscribe(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): bool {
        $subscriptionEntity = $this->getSubscription($availabilityNotificationSubscriptionTransfer);

        if ($subscriptionEntity !== null) {
            $subscriptionEntity->delete();

            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscription|null
     */
    protected function getSubscription(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ) {
        if ($availabilityNotificationSubscriptionTransfer->getSubscriptionKey() !== null) {
            return $this->queryContainer
                ->querySubscriptionBySubscriptionKey($availabilityNotificationSubscriptionTransfer->getSubscriptionKey())
                ->findOne();
        }

        if ($availabilityNotificationSubscriptionTransfer->getEmail() !== null) {
            return $this->queryContainer
                ->querySubscriptionByEmailAndSkuAndStore(
                    $availabilityNotificationSubscriptionTransfer->getEmail(),
                    $availabilityNotificationSubscriptionTransfer->getSku(),
                    $availabilityNotificationSubscriptionTransfer->getStore()
                )
                ->findOne();
        }

        if ($availabilityNotificationSubscriptionTransfer->getCustomerReference() !== null) {
            return $this->queryContainer
                ->querySubscriptionByCustomerReferenceAndSkuAndStore(
                    $availabilityNotificationSubscriptionTransfer->getCustomerReference(),
                    $availabilityNotificationSubscriptionTransfer->getSku(),
                    $availabilityNotificationSubscriptionTransfer->getStore()
                )
                ->findOne();
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscription
     */
    public function createSubscriptionEntityFromTransfer(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
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
