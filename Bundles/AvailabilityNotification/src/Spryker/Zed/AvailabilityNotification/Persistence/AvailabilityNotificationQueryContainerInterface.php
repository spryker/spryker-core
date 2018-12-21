<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscriptionQuery;

interface AvailabilityNotificationQueryContainerInterface
{
    /**
     * @api
     *
     * @param string $email
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscriptionQuery
     */
    public function querySubscriptionByEmailAndSkuAndStore(string $email, string $sku, StoreTransfer $storeTransfer): SpyAvailabilitySubscriptionQuery;

    /**
     * @api
     *
     * @param string $subscriptionKey
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscriptionQuery
     */
    public function querySubscriptionBySubscriptionKey(string $subscriptionKey): SpyAvailabilitySubscriptionQuery;

    /**
     * @api
     *
     * @param string $customerReference
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscriptionQuery
     */
    public function querySubscriptionByCustomerReferenceAndSkuAndStore(string $customerReference, string $sku, StoreTransfer $storeTransfer): SpyAvailabilitySubscriptionQuery;

    /**
     * @api
     *
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscriptionQuery
     */
    public function querySubscription();
}
