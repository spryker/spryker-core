<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class PushNotificationConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const PUSH_NOTIFICATION_DELETE_BATCH_SIZE = 500;

    /**
     * @var int
     */
    protected const PUSH_NOTIFICATION_SEND_BATCH_SIZE = 100;

    /**
     * @var list<string>
     */
    protected const GROUP_NAME_ALLOW_LIST = [];

    /**
     * @var string
     */
    protected const PUSH_NOTIFICATION_SUBSCRIPTION_TTL = 'P7D';

    /**
     * Specification:
     *  - Interval of how long the subscription is valid, required to feed to \DateTime object.
     *
     * @api
     *
     * @return string
     */
    public function getPushNotificationSubscriptionTTL(): string
    {
        return static::PUSH_NOTIFICATION_SUBSCRIPTION_TTL;
    }

    /**
     * Specification:
     *  - Limit for how much expired push notifications subscriptions should be got for deletion.
     *
     * @api
     *
     * @return int
     */
    public function getPushNotificationDeleteBatchSize(): int
    {
        return static::PUSH_NOTIFICATION_DELETE_BATCH_SIZE;
    }

    /**
     * Specification:
     *  - Limit for how much push notifications should be sent per one iteration.
     *
     * @api
     *
     * @return int
     */
    public function getPushNotificationSendBatchSize(): int
    {
        return static::PUSH_NOTIFICATION_SEND_BATCH_SIZE;
    }

    /**
     * Specification:
     *  - Returns a list of group names that are allowed to be used.
     *
     * @api
     *
     * @return list<string>
     */
    public function getGroupNameAllowList(): array
    {
        return static::GROUP_NAME_ALLOW_LIST;
    }
}
