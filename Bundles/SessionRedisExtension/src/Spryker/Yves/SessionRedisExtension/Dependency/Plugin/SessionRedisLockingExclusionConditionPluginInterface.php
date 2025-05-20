<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedisExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer;

/**
 * Use this plugin to determine if a session should be excluded from Redis locking.
 */
interface SessionRedisLockingExclusionConditionPluginInterface
{
    /**
     * Specification:
     * - Determines if the session should be excluded from Redis locking based on provided conditions.
     * - Returns `true` when the session should use non-locking Redis handler.
     * - Returns `false` when the session should use locking Redis handler.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer $redisLockingSessionHandlerConditionTransfer
     *
     * @return bool
     */
    public function checkCondition(RedisLockingSessionHandlerConditionTransfer $redisLockingSessionHandlerConditionTransfer): bool;
}
