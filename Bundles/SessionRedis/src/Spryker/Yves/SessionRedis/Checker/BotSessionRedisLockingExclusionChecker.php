<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis\Checker;

use Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer;
use Spryker\Yves\SessionRedis\SessionRedisConfig;

class BotSessionRedisLockingExclusionChecker implements BotSessionRedisLockingExclusionCheckerInterface
{
    /**
     * @var string
     */
    protected const HEADER_USER_AGENT_UPPERCASE = 'User-Agent';

    /**
     * @var string
     */
    protected const HEADER_USER_AGENT_LOWERCASE = 'user-agent';

    /**
     * @param \Spryker\Yves\SessionRedis\SessionRedisConfig $sessionRedisConfig
     */
    public function __construct(protected SessionRedisConfig $sessionRedisConfig)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer $redisLockingSessionHandlerConditionTransfer
     *
     * @return bool
     */
    public function checkCondition(
        RedisLockingSessionHandlerConditionTransfer $redisLockingSessionHandlerConditionTransfer
    ): bool {
        $headers = $redisLockingSessionHandlerConditionTransfer->getRequestHeaders();
        if (!$headers) {
            return false;
        }

        $userAgent = $this->extractUserAgent($headers);
        if (!$userAgent) {
            return false;
        }

        return $this->isBot($userAgent);
    }

    /**
     * @param array<string, mixed> $headers
     *
     * @return string
     */
    protected function extractUserAgent(array $headers): string
    {
        $userAgent = $headers[static::HEADER_USER_AGENT_UPPERCASE] ?? $headers[static::HEADER_USER_AGENT_LOWERCASE] ?? '';
        if (!$userAgent) {
            return '';
        }

        if (is_array($userAgent)) {
            return (string)reset($userAgent);
        }

        return (string)$userAgent;
    }

    /**
     * @param string $userAgent
     *
     * @return bool
     */
    protected function isBot(string $userAgent): bool
    {
        foreach ($this->sessionRedisConfig->getSessionRedisLockingExcludedBotUserAgents() as $botUserAgent) {
            if (stripos($userAgent, $botUserAgent) !== false) {
                return true;
            }
        }

        return false;
    }
}
