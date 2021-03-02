<?php

namespace Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\RestAvailabilityNotificationsAttributesTransfer;

class AvailabilityNotificationMapper implements AvailabilityNotificationMapperInterface
{
    public function mapAvailabilityNotificationSubscriptionTransferToRestAvailabilityNotificationsAttributesTransfer(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): RestAvailabilityNotificationsAttributesTransfer
    {
        $localeName = $availabilityNotificationSubscriptionTransfer->getLocale()
                    ? $availabilityNotificationSubscriptionTransfer->getLocale()->getLocaleName()
                    : null
        ;
        return (new RestAvailabilityNotificationsAttributesTransfer())
                ->fromArray($availabilityNotificationSubscriptionTransfer->toArray(), true)
                ->setLocale($localeName)
        ;
    }

}
