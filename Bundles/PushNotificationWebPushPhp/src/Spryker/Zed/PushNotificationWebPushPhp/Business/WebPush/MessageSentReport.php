<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business\WebPush;

use Minishlink\WebPush\MessageSentReport as MinishlinkMessageSentReport;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class MessageSentReport extends MinishlinkMessageSentReport
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
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface|null $response
     * @param bool $success
     * @param string $reason
     */
    public function __construct(
        int $pushNotificationIdentifier,
        int $pushNotificationSubscriptionIdentifier,
        RequestInterface $request,
        ?ResponseInterface $response = null,
        bool $success = true,
        string $reason = 'OK'
    ) {
        parent::__construct($request, $response, $success, $reason);

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
