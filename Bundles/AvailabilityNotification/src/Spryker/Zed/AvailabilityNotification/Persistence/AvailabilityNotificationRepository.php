<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationPersistenceFactory getFactory()
 */
class AvailabilityNotificationRepository extends AbstractRepository implements AvailabilityNotificationRepositoryInterface
{
    /**
     * @param string $email
     * @param string $sku
     * @param int $fkStore
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer|null
     */
    public function findOneByEmailAndSku(
        string $email,
        string $sku,
        int $fkStore
    ): ?AvailabilityNotificationSubscriptionTransfer {
        $query = $this->querySubscription()
            ->filterByEmail($email)
            ->filterBySku($sku)
            ->filterByFkStore($fkStore);
        $query->setIgnoreCase(true);

        $availabilityNotificationSubscriptionEntity = $query->findOne();

        if ($availabilityNotificationSubscriptionEntity === null) {
            return null;
        }

        return $this->getFactory()->createAvailabilityNotificationSubscriptionMapper()->mapAvailabilityNotificationSubscriptionTransfer($availabilityNotificationSubscriptionEntity);
    }

    /**
     * @param string $subscriptionKey
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer|null
     */
    public function findOneBySubscriptionKey(string $subscriptionKey): ?AvailabilityNotificationSubscriptionTransfer
    {
        $availabilityNotificationSubscriptionEntity = $this->querySubscription()
            ->filterBySubscriptionKey($subscriptionKey)
            ->findOne();

        if ($availabilityNotificationSubscriptionEntity === null) {
            return null;
        }

        return $this->getFactory()->createAvailabilityNotificationSubscriptionMapper()->mapAvailabilityNotificationSubscriptionTransfer($availabilityNotificationSubscriptionEntity);
    }

    /**
     * @param string $sku
     * @param int $fkStore
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer[]
     */
    public function findBySkuAndStore(string $sku, int $fkStore): array
    {
        $availabilityNotificationSubscriptionEntities = $this->querySubscription()
            ->filterBySku($sku)
            ->filterByFkStore($fkStore)
            ->find();

        return $this->getFactory()
            ->createAvailabilityNotificationSubscriptionMapper()
            ->mapAvailabilityNotificationSubscriptionTransferCollection($availabilityNotificationSubscriptionEntities);
    }

    /**
     * @param string $customerReference
     * @param string $sku
     * @param int $fkStore
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer|null
     */
    public function findOneByCustomerReferenceAndSku(
        string $customerReference,
        string $sku,
        int $fkStore
    ): ?AvailabilityNotificationSubscriptionTransfer {
        $availabilityNotificationSubscriptionEntity = $this->querySubscription()
            ->filterByCustomerReference($customerReference)
            ->filterBySku($sku)
            ->filterByFkStore($fkStore)
            ->findOne();

        if ($availabilityNotificationSubscriptionEntity === null) {
            return null;
        }

        return $this->getFactory()->createAvailabilityNotificationSubscriptionMapper()->mapAvailabilityNotificationSubscriptionTransfer($availabilityNotificationSubscriptionEntity);
    }

    /**
     * @param string $customerReference
     * @param int $fkStore
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer[]
     */
    public function findByCustomerReference(string $customerReference, int $fkStore): array
    {
        $availabilityNotificationSubscriptionEntities = $this->querySubscription()
            ->filterByCustomerReference($customerReference)
            ->filterByFkStore($fkStore)
            ->find();

        $availabilityNotificationSubscriptions = [];

        foreach ($availabilityNotificationSubscriptionEntities as $availabilityNotificationSubscriptionEntity) {
            $availabilityNotificationSubscriptions[] = $this->getFactory()
                ->createAvailabilityNotificationSubscriptionMapper()
                ->mapAvailabilityNotificationSubscriptionTransfer($availabilityNotificationSubscriptionEntity);
        }

        return $availabilityNotificationSubscriptions;
    }

    /**
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery
     */
    protected function querySubscription(): SpyAvailabilityNotificationSubscriptionQuery
    {
        return $this->getFactory()->createAvailabilityNotificationSubscriptionQuery();
    }
}
