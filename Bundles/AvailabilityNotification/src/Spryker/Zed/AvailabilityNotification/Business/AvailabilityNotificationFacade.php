<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business;

use Generated\Shared\Transfer\AvailabilitySubscriptionExistenceTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationBusinessFactory getFactory()
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface getRepository()
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface getEntityManager()()
 */
class AvailabilityNotificationFacade extends AbstractFacade implements AvailabilityNotificationFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function subscribe(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        $subscriptionResponse = $this->getFactory()
            ->createAvailabilitySubscriptionProcessor()
            ->process($availabilitySubscriptionTransfer);

        return $subscriptionResponse;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceTransfer
     */
    public function checkExistence(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionExistenceTransfer
    {
        return $this->getFactory()
            ->createAvailabilitySubscriptionChecker()
            ->checkExistence($availabilitySubscriptionTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function unsubscribe(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        $subscriptionResponse = $this->getFactory()
            ->createAvailabilityUnsubscriptionProcessor()
            ->process($availabilitySubscriptionTransfer);

        return $subscriptionResponse;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return void
     */
    public function anonymizeSubscription(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): void
    {
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function processAvailabilityNotificationSubscription(string $sku, StoreTransfer $storeTransfer): void
    {
        $this->getFactory()
            ->createAvailabilityNotificationMailProcessor()
            ->processProductBecomeAvailableSubscription($sku, $storeTransfer);
    }
}
