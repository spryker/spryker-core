<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis\Plugin\SessionRedisLockingExclusion;

use Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\SessionRedisExtension\Dependency\Plugin\SessionRedisLockingExclusionConditionPluginInterface;

/**
 * @method \Spryker\Yves\SessionRedis\SessionRedisConfig getConfig()
 * @method \Spryker\Yves\SessionRedis\SessionRedisFactory getFactory()
 */
class BotSessionRedisLockingExclusionConditionPlugin extends AbstractPlugin implements SessionRedisLockingExclusionConditionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if the request's user agent contains any of the bot patterns configured in {@link \Spryker\Yves\SessionRedis\SessionRedisConfig::getSessionRedisLockingExcludedBotUserAgents()}.
     * - Returns `true` if the user agent matches any of the configured bot patterns, indicating that session locking should be bypassed.
     * - Returns `false` if the user agent does not match any patterns or the request headers are not set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer $redisLockingSessionHandlerConditionTransfer
     *
     * @return bool
     */
    public function checkCondition(RedisLockingSessionHandlerConditionTransfer $redisLockingSessionHandlerConditionTransfer): bool
    {
        return $this->getFactory()->createBotSessionRedisLockingExclusionChecker()->checkCondition($redisLockingSessionHandlerConditionTransfer);
    }
}
