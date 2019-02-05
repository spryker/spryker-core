<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;

interface AvailabilityNotificationRepositoryInterface
{
    /**
     * @param string $email
     * @param string $sku
     * @param int $fkStore
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer|null
     */
    public function findOneByEmailAndSku(string $email, string $sku, int $fkStore): ?AvailabilitySubscriptionTransfer;

    /**
     * @param string $subscriptionKey
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer|null
     */
    public function findOneBySubscriptionKey(string $subscriptionKey): ?AvailabilitySubscriptionTransfer;

    /**
     * @param string $customerReference
     * @param string $sku
     * @param int $fkStore
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer|null
     */
    public function findOneByCustomerReferenceAndSku(string $customerReference, string $sku, int $fkStore): ?AvailabilitySubscriptionTransfer;

    /**
     * @param string $sku
     * @param int $fkStore
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer[]
     */
    public function findBySkuAndStore(string $sku, int $fkStore): array;

    /**
     * @param string $customerReference
     * @param int $fkStore
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer[]
     */
    public function findByCustomerReference(string $customerReference, int $fkStore): array;
}
