<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface;

class AvailabilityNotificationSubscriptionSaver implements AvailabilityNotificationSubscriptionSaverInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionKeyGeneratorInterface
     */
    protected $keyGenerator;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface
     */
    protected $availabilityNotificationToStoreFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface
     */
    protected $availabilityNotificationToLocaleFacade;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface $entityManager
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionKeyGeneratorInterface $keyGenerator
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface $availabilityNotificationToStoreFacade
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface $availabilityNotificationToLocaleFacade
     */
    public function __construct(
        AvailabilityNotificationEntityManagerInterface $entityManager,
        AvailabilityNotificationSubscriptionKeyGeneratorInterface $keyGenerator,
        AvailabilityNotificationToStoreFacadeInterface $availabilityNotificationToStoreFacade,
        AvailabilityNotificationToLocaleFacadeInterface $availabilityNotificationToLocaleFacade
    ) {
        $this->entityManager = $entityManager;
        $this->keyGenerator = $keyGenerator;
        $this->availabilityNotificationToStoreFacade = $availabilityNotificationToStoreFacade;
        $this->availabilityNotificationToLocaleFacade = $availabilityNotificationToLocaleFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer
     */
    public function save(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionTransfer
    {
        $availabilityNotificationSubscriptionTransfer->requireSku();
        $availabilityNotificationSubscriptionTransfer->requireEmail();

        $subscriptionKey = $this->keyGenerator->generateKey();
        $availabilityNotificationSubscriptionTransfer->setSubscriptionKey($subscriptionKey);

        $store = $this->availabilityNotificationToStoreFacade->getCurrentStore();
        $availabilityNotificationSubscriptionTransfer->setStore($store);

        $locale = $this->availabilityNotificationToLocaleFacade->getCurrentLocale();
        $availabilityNotificationSubscriptionTransfer->setLocale($locale);

        $this->entityManager->saveAvailabilityNotificationSubscription($availabilityNotificationSubscriptionTransfer);

        return $availabilityNotificationSubscriptionTransfer;
    }
}
