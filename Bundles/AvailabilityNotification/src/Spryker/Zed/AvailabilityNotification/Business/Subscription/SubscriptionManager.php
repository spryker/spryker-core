<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscription;
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
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\SubscriptionKeyGeneratorInterface $subscriptionKeyGenerator
     */
    public function __construct(
        AvailabilityNotificationQueryContainerInterface $queryContainer,
        SubscriptionKeyGeneratorInterface $subscriptionKeyGenerator
    ) {
        $this->queryContainer = $queryContainer;
        $this->subscriptionKeyGenerator = $subscriptionKeyGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return void
     */
    public function subscribe(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): void
    {
        $availabilityNotificationSubscriptionTransfer->requireIdAvailabilityNotificationSubscription();

        $subscriptionEntity = new SpyAvailabilityNotificationSubscription();
        $subscriptionEntity->fromArray($availabilityNotificationSubscriptionTransfer->toArray());
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

        $subscriptionCount = $this->queryContainer
            ->querySubscriptionByEmailAndSku($availabilityNotificationSubscriptionTransfer->getEmail(), $availabilityNotificationSubscriptionTransfer->getSku())
            ->count();

        return $subscriptionCount > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return bool
     */
    public function unsubscribe(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): bool
    {
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
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscription|null
     */
    protected function getSubscription(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer) {
        if ($availabilityNotificationSubscriptionTransfer->getSubscriptionKey() !== null) {
            $subscriptionEntity = $this->queryContainer
                ->querySubscriptionBySubscriptionKeyAndSku(
                    $availabilityNotificationSubscriptionTransfer->getSubscriptionKey(),
                    $availabilityNotificationSubscriptionTransfer->getSku()
                )
                ->findOne();

            return $subscriptionEntity;
        }

        if ($availabilityNotificationSubscriptionTransfer->getEmail() !== null) {
            $subscriptionEntity = $this->queryContainer
                ->querySubscriptionByEmailAndSku(
                    $availabilityNotificationSubscriptionTransfer->getEmail(),
                    $availabilityNotificationSubscriptionTransfer->getSku()
                )
                ->findOne();

            return $subscriptionEntity;
        }

        if ($availabilityNotificationSubscriptionTransfer->getCustomerReference() !== null) {
            $subscriptionEntity = $this->queryContainer
                ->querySubscriptionByIdCustomerAndSku(
                    $availabilityNotificationSubscriptionTransfer->getCustomerReference(),
                    $availabilityNotificationSubscriptionTransfer->getSku()
                )
                ->findOne();

            return $subscriptionEntity;
        }

        return null;
    }

    /**
     * @param string $email
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer|null
     */
    public function findSubscriptionByEmail($email)
    {
        $subscriptionEntity = $this->queryContainer->querySubscription()
            ->filterByEmail($email)
            ->findOne();

        if ($subscriptionEntity === null) {
            return null;
        }

        return $this->convertSubscriptionEntityToTransfer($subscriptionEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer
     */
    public function createSubscriptionFromTransfer(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionTransfer
    {
        $subscriptionEntity = new SpyAvailabilityNotificationSubscription();
        $subscriptionEntity->fromArray($availabilityNotificationSubscriptionTransfer->toArray());

        $subscriptionKey = $this->subscriptionKeyGenerator->generateKey();

        $subscriptionEntity->setSubscriptionKey($subscriptionKey);
        $subscriptionEntity->save();

        return $this->convertSubscriptionEntityToTransfer($subscriptionEntity);
    }

    /**
     * @param \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscription $subscriptionEntity
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer
     */
    protected function convertSubscriptionEntityToTransfer(SpyAvailabilityNotificationSubscription $subscriptionEntity
    ): AvailabilityNotificationSubscriptionTransfer {
        $subscriptionTransfer = new AvailabilityNotificationSubscriptionTransfer();
        $subscriptionTransfer->fromArray($subscriptionEntity->toArray(), true);

        return $subscriptionTransfer;
    }
}
