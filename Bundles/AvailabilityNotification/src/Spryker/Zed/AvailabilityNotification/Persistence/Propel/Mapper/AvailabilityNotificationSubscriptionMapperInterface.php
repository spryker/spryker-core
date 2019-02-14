<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscription;
use Propel\Runtime\Collection\ObjectCollection;

interface AvailabilityNotificationSubscriptionMapperInterface
{
    /**
     * @param \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscription $availabilityNotificationSubscriptionEntityTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer
     */
    public function mapAvailabilityNotificationSubscriptionTransfer(SpyAvailabilityNotificationSubscription $availabilityNotificationSubscriptionEntityTransfer): AvailabilityNotificationSubscriptionTransfer;

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $availabilityNotificationSubscriptionEntities
     *
     * @return array
     */
    public function mapAvailabilityNotificationSubscriptionTransferCollection(ObjectCollection $availabilityNotificationSubscriptionEntities): array;
}
