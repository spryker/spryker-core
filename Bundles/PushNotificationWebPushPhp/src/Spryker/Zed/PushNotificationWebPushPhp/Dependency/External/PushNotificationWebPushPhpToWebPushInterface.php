<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Dependency\External;

use Generator;
use Minishlink\WebPush\SubscriptionInterface;

interface PushNotificationWebPushPhpToWebPushInterface
{
    /**
     * @param \Minishlink\WebPush\SubscriptionInterface $subscription
     * @param string|null $payload
     * @param array<mixed> $options
     * @param array<string, mixed> $auth
     * @param int|null $pushNotificationIdentifier
     * @param int|null $pushNotificationSubscriptionIdentifier
     *
     * @return void
     */
    public function queueNotification(
        SubscriptionInterface $subscription,
        ?string $payload = null,
        array $options = [],
        array $auth = [],
        ?int $pushNotificationIdentifier = null,
        ?int $pushNotificationSubscriptionIdentifier = null
    ): void;

    /**
     * @param int|null $batchSize
     *
     * @return \Generator<int, \Minishlink\WebPush\MessageSentReport>
     */
    public function flush(?int $batchSize = null): Generator;
}
