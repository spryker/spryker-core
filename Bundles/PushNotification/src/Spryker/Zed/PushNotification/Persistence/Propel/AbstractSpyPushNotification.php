<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Persistence\Propel;

use ArrayObject;
use Orm\Zed\PushNotification\Persistence\Base\SpyPushNotification as BaseSpyPushNotification;

abstract class AbstractSpyPushNotification extends BaseSpyPushNotification
{
    /**
     * @var \ArrayObject<int, \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscription>
     */
    protected ArrayObject $pushNotificationSubscriptions;

    public function __construct()
    {
        $this->pushNotificationSubscriptions = new ArrayObject();

        parent::__construct();
    }

    /**
     * @return \ArrayObject<int, \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscription>
     */
    public function getPushNotificationSubscriptions(): ArrayObject
    {
        return $this->pushNotificationSubscriptions;
    }

    /**
     * @param \ArrayObject<int, \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscription> $pushNotificationSubscriptions
     *
     * @return void
     */
    public function setPushNotificationSubscriptions(ArrayObject $pushNotificationSubscriptions): void
    {
        $this->pushNotificationSubscriptions = $pushNotificationSubscriptions;
    }
}
