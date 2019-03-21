<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SessionRedis\Plugin\Session;

use SessionHandlerInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerPluginInterface;
use Spryker\Shared\SessionRedis\SessionRedisConfig;

/**
 * @method \Spryker\Client\SessionRedis\SessionRedisFactory getFactory()
 */
class SessionHandlerRedisPlugin extends AbstractPlugin implements SessionHandlerPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getSessionHandlerName(): string
    {
        return SessionRedisConfig::SESSION_HANDLER_REDIS_NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \SessionHandlerInterface
     */
    public function getSessionHandler(): SessionHandlerInterface
    {
        return $this->getFactory()->createSessionRedisHandler();
    }
}
