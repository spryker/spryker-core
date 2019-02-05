<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscription;
use Propel\Runtime\Collection\ObjectCollection;

interface AvailabilitySubscriptionMapperInterface
{
    /**
     * @param \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscription $availabilitySubscriptionEntityTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer
     */
    public function mapAvailabilitySubscriptionTransfer(SpyAvailabilitySubscription $availabilitySubscriptionEntityTransfer): AvailabilitySubscriptionTransfer;

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $availabilitySubscriptionEntities
     *
     * @return array
     */
    public function mapAvailabilitySubscriptionTransferCollection(ObjectCollection $availabilitySubscriptionEntities): array;
}
