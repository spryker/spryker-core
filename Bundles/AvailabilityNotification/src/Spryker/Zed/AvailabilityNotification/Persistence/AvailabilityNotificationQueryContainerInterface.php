<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence;

interface AvailabilityNotificationQueryContainerInterface
{
    /**
     * @api
     *
     * @param string $email
     * @param string $sku
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery
     */
    public function querySubscriptionByEmailAndSku($email, $sku);

    /**
     * @api
     *
     * @param string $subscriptionKey
     * @param string $sku
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery
     */
    public function querySubscriptionBySubscriptionKeyAndSku($subscriptionKey, $sku);

    /**
     * @api
     *
     * @param string $idCustomer
     * @param string $sku
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery
     */
    public function querySubscriptionByCustomerReferenceAndSku($idCustomer, $sku);

    /**
     * @api
     *
     * @param string $idCustomer
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery
     */
    public function querySubscriptionByCustomerReference($idCustomer);

    /**
     * @api
     *
     * @param string $email
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery
     */
    public function querySubscriptionByEmail($email);

    /**
     * @api
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery
     */
    public function querySubscription();
}
