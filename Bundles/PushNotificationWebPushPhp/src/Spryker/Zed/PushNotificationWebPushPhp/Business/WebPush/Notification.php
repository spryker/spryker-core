<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business\WebPush;

use Minishlink\WebPush\Notification as MinishlinkNotification;
use Minishlink\WebPush\SubscriptionInterface;

class Notification extends MinishlinkNotification
{
    /**
     * @var int
     */
    protected int $pushNotificationIdentifier;

    /**
     * @var int
     */
    protected int $pushNotificationSubscriptionIdentifier;

    /**
     * @param int $pushNotificationIdentifier
     * @param int $pushNotificationSubscriptionIdentifier
     * @param \Minishlink\WebPush\SubscriptionInterface $subscription
     * @param string|null $payload
     * @param array<string, mixed> $options
     * @param array<string, mixed> $auth
     */
    public function __construct(
        int $pushNotificationIdentifier,
        int $pushNotificationSubscriptionIdentifier,
        SubscriptionInterface $subscription,
        ?string $payload,
        array $options,
        array $auth
    ) {
        parent::__construct($subscription, $payload, $options, $auth);

        $this->pushNotificationIdentifier = $pushNotificationIdentifier;
        $this->pushNotificationSubscriptionIdentifier = $pushNotificationSubscriptionIdentifier;
    }

    /**
     * @return int
     */
    public function getPushNotificationIdentifier(): int
    {
        return $this->pushNotificationIdentifier;
    }

    /**
     * @return int
     */
    public function getPushNotificationSubscriptionIdentifier(): int
    {
        return $this->pushNotificationSubscriptionIdentifier;
    }
}
