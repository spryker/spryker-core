<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business;

use Generated\Shared\Transfer\AvailabilityNotificationDataTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationBusinessFactory getFactory()
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface getRepository()
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface getEntityManager()
 */
class AvailabilityNotificationFacade extends AbstractFacade implements AvailabilityNotificationFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function subscribe(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer
    {
        $subscriptionResponse = $this->getFactory()
            ->createAvailabilityNotificationSubscriber()
            ->subscribe($availabilityNotificationSubscriptionTransfer);

        return $subscriptionResponse;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function unsubscribeBySubscriptionKey(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer
    {
        $subscriptionResponse = $this->getFactory()
            ->createAvailabilityNotificationUnsubscriber()
            ->unsubscribeBySubscriptionKey($availabilityNotificationSubscriptionTransfer);

        return $subscriptionResponse;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function unsubscribeByCustomerReferenceAndSku(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): AvailabilityNotificationSubscriptionResponseTransfer
    {
        $subscriptionResponse = $this->getFactory()
            ->createAvailabilityNotificationUnsubscriber()
            ->unsubscribeByCustomerReferenceAndSku($availabilityNotificationSubscriptionTransfer);

        return $subscriptionResponse;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function anonymizeSubscription(CustomerTransfer $customerTransfer): void
    {
        $this->getFactory()
            ->createSubscriptionAnonymizer()
            ->anonymizeSubscription($customerTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationDataTransfer $availabilityNotificationDataTransfer
     *
     * @return void
     */
    public function sendAvailabilityNotificationSubscriptionNotification(AvailabilityNotificationDataTransfer $availabilityNotificationDataTransfer): void
    {
        $this->getFactory()
            ->createProductBecomeAvailableNotificationSender()
            ->send($availabilityNotificationDataTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandCustomerTransferWithAvailabilityNotificationSubscriptionList(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        return $this->getFactory()
            ->createCustomerExpander()
            ->expandCustomerTransferWithAvailabilityNotificationSubscriptionList($customerTransfer);
    }
}
