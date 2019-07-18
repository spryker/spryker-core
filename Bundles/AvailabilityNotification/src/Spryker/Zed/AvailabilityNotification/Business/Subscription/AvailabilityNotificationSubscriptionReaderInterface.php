<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;

interface AvailabilityNotificationSubscriptionReaderInterface
{
    /**
     * @param string $email
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer|null
     */
    public function findOneByEmailAndSku(string $email, string $sku): ?AvailabilityNotificationSubscriptionTransfer;

    /**
     * @param string $subscriptionKey
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer|null
     */
    public function findOneBySubscriptionKey(string $subscriptionKey): ?AvailabilityNotificationSubscriptionTransfer;

    /**
     * @param string $customerReference
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer|null
     */
    public function findOneByCustomerReferenceAndSku(
        string $customerReference,
        string $sku
    ): ?AvailabilityNotificationSubscriptionTransfer;

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer[]
     */
    public function findByCustomerReference(string $customerReference): array;
}
