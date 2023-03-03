<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Dependency\External;

use Minishlink\WebPush\Subscription;

class PushNotificationWebPushPhpToSubscriptionAdapter implements PushNotificationWebPushPhpToSubscriptionInterface
{
    /**
     * @param array<mixed> $associativeArray
     *
     * @return \Minishlink\WebPush\Subscription
     */
    public function create(array $associativeArray): Subscription
    {
        return Subscription::create($associativeArray);
    }
}
