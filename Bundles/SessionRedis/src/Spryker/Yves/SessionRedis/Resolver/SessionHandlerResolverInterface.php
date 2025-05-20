<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis\Resolver;

use SessionHandlerInterface;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;

interface SessionHandlerResolverInterface
{
    /**
     * @param \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface $sessionRedisWrapper
     *
     * @return \SessionHandlerInterface
     */
    public function resolveConfigurableRedisLockingSessionHandler(
        SessionRedisWrapperInterface $sessionRedisWrapper
    ): SessionHandlerInterface;
}
