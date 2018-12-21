<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence;

use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscriptionQuery;
use Spryker\Zed\AvailabilityNotification\Persistence\Propel\Mapper\AvailabilitySubscriptionMapper;
use Spryker\Zed\AvailabilityNotification\Persistence\Propel\Mapper\AvailabilitySubscriptionMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig getConfig()
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface getQueryContainer()
 */
class AvailabilityNotificationPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscriptionQuery
     */
    public function createAvailabilitySubscriptionQuery(): SpyAvailabilitySubscriptionQuery
    {
        return SpyAvailabilitySubscriptionQuery::create();
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Persistence\Propel\Mapper\AvailabilitySubscriptionMapperInterface
     */
    public function createAvailabilitySubscriptionMapper(): AvailabilitySubscriptionMapperInterface
    {
        return new AvailabilitySubscriptionMapper();
    }
}
