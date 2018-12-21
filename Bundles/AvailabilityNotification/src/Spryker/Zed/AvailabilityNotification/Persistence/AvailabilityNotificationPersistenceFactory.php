<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence;

use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscriptionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig getConfig()
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationQueryContainerInterface getQueryContainer()
 */
class AvailabilityNotificationPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscriptionQuery
     */
    public function createAvailabilityNotificationSubscriptionQuery()
    {
        return SpyAvailabilitySubscriptionQuery::create();
    }
}
