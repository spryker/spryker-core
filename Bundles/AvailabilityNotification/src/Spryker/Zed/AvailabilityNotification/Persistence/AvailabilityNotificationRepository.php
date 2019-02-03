<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence;

use Generated\Shared\Transfer\AvailabilitySubscriptionCollectionTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscriptionQuery;
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
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer|null
     */
    public function findOneByEmailAndSku(
        string $email,
        string $sku,
        int $fkStore
    ): ?AvailabilitySubscriptionTransfer {
        $availabilitySubscriptionEntity = $this->querySubscription()
            ->filterByEmail($email)
            ->filterBySku($sku)
            ->filterByFkStore($fkStore)
            ->setIgnoreCase(true)
            ->findOne();

        if ($availabilitySubscriptionEntity === null) {
            return null;
        }

        return $this->getFactory()->createAvailabilitySubscriptionMapper()->mapAvailabilitySubscriptionTransfer($availabilitySubscriptionEntity);
    }

    /**
     * @param string $subscriptionKey
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer|null
     */
    public function findOneBySubscriptionKey(string $subscriptionKey): ?AvailabilitySubscriptionTransfer
    {
        $availabilitySubscriptionEntity = $this->querySubscription()
            ->filterBySubscriptionKey($subscriptionKey)
            ->findOne();

        if ($availabilitySubscriptionEntity === null) {
            return null;
        }

        return $this->getFactory()->createAvailabilitySubscriptionMapper()->mapAvailabilitySubscriptionTransfer($availabilitySubscriptionEntity);
    }

    /**
     * @param string $sku
     * @param int $fkStore
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionCollectionTransfer
     */
    public function findBySkuAndStore(string $sku, int $fkStore): AvailabilitySubscriptionCollectionTransfer
    {
        $availabilitySubscriptionEntities = $this->querySubscription()
            ->filterBySku($sku)
            ->filterByFkStore($fkStore)
            ->find();

        return $this->getFactory()
            ->createAvailabilitySubscriptionMapper()
            ->mapAvailabilitySubscriptionTransferCollection($availabilitySubscriptionEntities);
    }

    /**
     * @param string $customerReference
     * @param string $sku
     * @param int $fkStore
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer|null
     */
    public function findOneByCustomerReferenceAndSku(
        string $customerReference,
        string $sku,
        int $fkStore
    ): ?AvailabilitySubscriptionTransfer {
        $availabilitySubscriptionEntity = $this->querySubscription()
            ->filterByCustomerReference($customerReference)
            ->filterBySku($sku)
            ->filterByFkStore($fkStore)
            ->setIgnoreCase(true)
            ->findOne();

        if ($availabilitySubscriptionEntity === null) {
            return null;
        }

        return $this->getFactory()->createAvailabilitySubscriptionMapper()->mapAvailabilitySubscriptionTransfer($availabilitySubscriptionEntity);
    }

    /**
     * @param string $customerReference
     * @param int $fkStore
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionCollectionTransfer
     */
    public function findByCustomerReference(string $customerReference, int $fkStore): AvailabilitySubscriptionCollectionTransfer
    {
        $availabilitySubscriptionEntities = $this->querySubscription()
            ->filterByCustomerReference($customerReference)
            ->filterByFkStore($fkStore)
            ->find();

        $availabilitySubscriptionCollection = new AvailabilitySubscriptionCollectionTransfer();

        foreach ($availabilitySubscriptionEntities as $availabilitySubscriptionEntity) {
            $availabilitySubscription = $this->getFactory()->createAvailabilitySubscriptionMapper()->mapAvailabilitySubscriptionTransfer($availabilitySubscriptionEntity);
            $availabilitySubscriptionCollection->addAvailabilitySubscription($availabilitySubscription);
        }

        return $availabilitySubscriptionCollection;
    }

    /**
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscriptionQuery
     */
    protected function querySubscription(): SpyAvailabilitySubscriptionQuery
    {
        return $this->getFactory()->createAvailabilitySubscriptionQuery();
    }
}
