<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionRedis\Communication\Plugin\Session;

use Spryker\Shared\SessionRedis\Handler\SessionHandlerInterface;
use Spryker\Shared\SessionRedis\SessionRedisConfig;

/**
 * @method \Spryker\Zed\SessionRedis\SessionRedisConfig getConfig()
 */
class SessionHandlerRedisLockingPlugin extends AbstractSessionHandlerRedisPlugin
{
    /**
     * @api
     *
     * @return string
     */
    public function getSessionHandlerName(): string
    {
        return SessionRedisConfig::SESSION_HANDLER_REDIS_LOCKING_NAME;
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Handler\SessionHandlerInterface
     */
    protected function getSessionHandler(): SessionHandlerInterface
    {
        return $this->getFactory()->createSessionHandlerRedisLocking();
    }
}
