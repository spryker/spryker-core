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
class UrlSessionRedisLockingExclusionConditionPlugin extends AbstractPlugin implements SessionRedisLockingExclusionConditionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if the request URI matches any of the URL patterns configured in {@link \Spryker\Yves\SessionRedis\SessionRedisConfig::getSessionRedisLockingExcludedUrlPatterns()}.
     * - Returns `true` if the URI matches any of the configured URL patterns, indicating that session locking should be bypassed.
     * - Returns `false` if the URI doesn't match any patterns or the request URI is not set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer $redisLockingSessionHandlerConditionTransfer
     *
     * @return bool
     */
    public function checkCondition(RedisLockingSessionHandlerConditionTransfer $redisLockingSessionHandlerConditionTransfer): bool
    {
        return $this->getFactory()->createUrlSessionRedisLockingExclusionChecker()->checkCondition($redisLockingSessionHandlerConditionTransfer);
    }
}
