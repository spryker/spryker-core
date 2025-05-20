<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis\Checker;

use Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer;

interface BotSessionRedisLockingExclusionCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer $redisLockingSessionHandlerConditionTransfer
     *
     * @return bool
     */
    public function checkCondition(RedisLockingSessionHandlerConditionTransfer $redisLockingSessionHandlerConditionTransfer): bool;
}
