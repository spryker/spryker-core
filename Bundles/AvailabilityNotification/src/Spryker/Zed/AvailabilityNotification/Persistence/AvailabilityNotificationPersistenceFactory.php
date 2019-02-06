<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence;

use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery;
use Spryker\Zed\AvailabilityNotification\Persistence\Propel\Mapper\AvailabilityNotificationSubscriptionMapper;
use Spryker\Zed\AvailabilityNotification\Persistence\Propel\Mapper\AvailabilityNotificationSubscriptionMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface getRepository()
 * @method \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig getConfig()
 */
class AvailabilityNotificationPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery
     */
    public function createAvailabilityNotificationSubscriptionQuery(): SpyAvailabilityNotificationSubscriptionQuery
    {
        return SpyAvailabilityNotificationSubscriptionQuery::create();
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Persistence\Propel\Mapper\AvailabilityNotificationSubscriptionMapperInterface
     */
    public function createAvailabilityNotificationSubscriptionMapper(): AvailabilityNotificationSubscriptionMapperInterface
    {
        return new AvailabilityNotificationSubscriptionMapper();
    }
}
