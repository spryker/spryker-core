<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionCollectionTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;

interface AvailabilitySubscriptionReaderInterface
{
    /**
     * @param string $email
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer|null
     */
    public function findOneByEmailAndSku(string $email, string $sku): ?AvailabilitySubscriptionTransfer;

    /**
     * @param string $subscriptionKey
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer|null
     */
    public function findOneBySubscriptionKey(string $subscriptionKey): ?AvailabilitySubscriptionTransfer;

    /**
     * @param string $customerReference
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer|null
     */
    public function findOneByCustomerReferenceAndSku(string $customerReference, string $sku): ?AvailabilitySubscriptionTransfer;

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionCollectionTransfer
     */
    public function findByCustomerReference(string $customerReference): AvailabilitySubscriptionCollectionTransfer;
}
