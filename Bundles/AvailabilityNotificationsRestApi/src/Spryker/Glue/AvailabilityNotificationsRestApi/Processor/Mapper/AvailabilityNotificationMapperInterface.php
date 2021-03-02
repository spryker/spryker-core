<?php

namespace Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\RestAvailabilityNotificationsAttributesTransfer;

interface AvailabilityNotificationMapperInterface
{
    public function mapAvailabilityNotificationSubscriptionTransferToRestAvailabilityNotificationsAttributesTransfer(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): RestAvailabilityNotificationsAttributesTransfer;
}
