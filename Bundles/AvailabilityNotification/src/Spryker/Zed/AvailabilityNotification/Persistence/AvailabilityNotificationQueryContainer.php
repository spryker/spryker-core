<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationPersistenceFactory getFactory()
 */
class AvailabilityNotificationQueryContainer extends AbstractQueryContainer implements AvailabilityNotificationQueryContainerInterface
{
    /**
     * @api
     *
     * @param string $email
     * @param string $sku
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery
     */
    public function querySubscriptionByEmailAndSku($email, $sku)
    {
        $subscriptionQuery = $this->querySubscription()
            ->filterBySku($sku)
            ->filterByEmail($email)
            ->setIgnoreCase(true);

        return $subscriptionQuery;
    }

    /**
     * @api
     *
     * @param string $subscriptionKey
     * @param string $sku
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery
     */
    public function querySubscriptionBySubscriptionKeyAndSku($subscriptionKey, $sku)
    {
        $subscriptionQuery = $this->querySubscription()
            ->filterBySku($sku)
            ->filterBySubscriptionKey($subscriptionKey);

        return $subscriptionQuery;
    }

    /**
     * @api
     *
     * @param int $customerReference
     * @param string $sku
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery
     */
    public function querySubscriptionByCustomerReferenceAndSku($customerReference, $sku)
    {
        $subscriptionQuery = $this->querySubscription()
            ->filterBySku($sku)
            ->filterByCustomerReference($customerReference);

        return $subscriptionQuery;
    }

    /**
     * @api
     *
     * @param string $customerReference
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery
     */
    public function querySubscriptionByCustomerReference($customerReference)
    {
        return $this->querySubscription()
            ->filterByCustomerReference($customerReference);
    }

    /**
     * @api
     *
     * @param string $email
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery
     */
    public function querySubscriptionByEmail($email)
    {
        return $this->querySubscription()
            ->filterByEmail($email)
            ->setIgnoreCase(true);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery
     */
    public function querySubscription()
    {
        return $this->getFactory()->createAvailabilityNotificationSubscriptionQuery();
    }
}
