<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis\Checker;

use Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer;
use Spryker\Yves\SessionRedis\SessionRedisConfig;

class UrlSessionRedisLockingExclusionChecker implements UrlSessionRedisLockingExclusionCheckerInterface
{
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
        if (!$redisLockingSessionHandlerConditionTransfer->getRequestUri()) {
            return false;
        }

        foreach ($this->sessionRedisConfig->getSessionRedisLockingExcludedUrlPatterns() as $urlPattern) {
            if (preg_match($urlPattern, $redisLockingSessionHandlerConditionTransfer->getRequestUri())) {
                return true;
            }
        }

        return false;
    }
}
