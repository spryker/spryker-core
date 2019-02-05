<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface;

class AvailabilitySubscriptionReader implements AvailabilitySubscriptionReaderInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface
     */
    protected $availabilityNotificationRepository;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface $availabilityNotificationRepository
     */
    public function __construct(
        AvailabilityNotificationToStoreFacadeInterface $storeFacade,
        AvailabilityNotificationRepositoryInterface $availabilityNotificationRepository
    ) {
        $this->storeFacade = $storeFacade;
        $this->availabilityNotificationRepository = $availabilityNotificationRepository;
    }

    /**
     * @param string $email
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer|null
     */
    public function findOneByEmailAndSku(string $email, string $sku): ?AvailabilitySubscriptionTransfer
    {
        return $this->availabilityNotificationRepository
            ->findOneByEmailAndSku($email, $sku, $this->storeFacade->getCurrentStore()->getIdStore());
    }

    /**
     * @param string $subscriptionKey
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer|null
     */
    public function findOneBySubscriptionKey(string $subscriptionKey): ?AvailabilitySubscriptionTransfer
    {
        return $this->availabilityNotificationRepository
            ->findOneBySubscriptionKey($subscriptionKey);
    }

    /**
     * @param string $customerReference
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer|null
     */
    public function findOneByCustomerReferenceAndSku(
        string $customerReference,
        string $sku
    ): ?AvailabilitySubscriptionTransfer {
        return $this->availabilityNotificationRepository
            ->findOneByCustomerReferenceAndSku($customerReference, $sku, $this->storeFacade->getCurrentStore()->getIdStore());
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer[]
     */
    public function findByCustomerReference(string $customerReference): array
    {
        return $this->availabilityNotificationRepository
            ->findByCustomerReference($customerReference, $this->storeFacade->getCurrentStore()->getIdStore());
    }
}
