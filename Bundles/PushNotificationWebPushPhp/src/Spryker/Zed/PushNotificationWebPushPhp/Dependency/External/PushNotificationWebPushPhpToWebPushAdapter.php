<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Dependency\External;

use Generator;
use Minishlink\WebPush\SubscriptionInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\WebPush\WebPush;

class PushNotificationWebPushPhpToWebPushAdapter implements PushNotificationWebPushPhpToWebPushInterface
{
    /**
     * @var \Spryker\Zed\PushNotificationWebPushPhp\Business\WebPush\WebPush
     */
    protected WebPush $webPush;

    /**
     * @param \Spryker\Zed\PushNotificationWebPushPhp\Business\WebPush\WebPush $webPush
     */
    public function __construct(WebPush $webPush)
    {
        $this->webPush = $webPush;
    }

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
    ): void {
        $this->webPush->queueNotification(
            $subscription,
            $payload,
            $options,
            $auth,
            $pushNotificationIdentifier,
            $pushNotificationSubscriptionIdentifier,
        );
    }

    /**
     * @param int|null $batchSize
     *
     * @return \Generator<int, \Minishlink\WebPush\MessageSentReport>
     */
    public function flush(?int $batchSize = null): Generator
    {
        return $this->webPush->flush($batchSize);
    }
}
