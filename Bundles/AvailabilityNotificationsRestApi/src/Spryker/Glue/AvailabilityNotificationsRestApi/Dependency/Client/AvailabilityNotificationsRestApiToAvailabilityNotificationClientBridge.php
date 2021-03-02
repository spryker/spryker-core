<?php

namespace Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client;

use Generated\Shared\Transfer\AvailabilityNotificationCriteriaTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Spryker\Client\AvailabilityNotification\AvailabilityNotificationClientInterface;

class AvailabilityNotificationsRestApiToAvailabilityNotificationClientBridge implements AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface
{
    /**
     * @var \Spryker\Client\AvailabilityNotification\AvailabilityNotificationClientInterface
     */
    protected $availabilityNotificationClient;

    /**
     * @param \Spryker\Client\AvailabilityNotification\AvailabilityNotificationClientInterface $availabilityNotificationClient
     */
    public function __construct($availabilityNotificationClient)
    {
        $this->availabilityNotificationClient = $availabilityNotificationClient;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function subscribe(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): AvailabilityNotificationSubscriptionResponseTransfer
    {
        return $this->availabilityNotificationClient->subscribe($availabilityNotificationSubscriptionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function unsubscribeBySubscriptionKey(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): AvailabilityNotificationSubscriptionResponseTransfer
    {
        return $this->availabilityNotificationClient->unsubscribeBySubscriptionKey($availabilityNotificationSubscriptionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationCriteriaTransfer $availabilityNotificationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer
     */
    public function getByCustomerAction(
        AvailabilityNotificationCriteriaTransfer $availabilityNotificationCriteriaTransfer
    ): AvailabilityNotificationSubscriptionCollectionTransfer
    {
        return $this->availabilityNotificationClient->getByCustomerAction($availabilityNotificationCriteriaTransfer);
    }
}
