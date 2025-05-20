<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis\Plugin\Session;

use SessionHandlerInterface;
use Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\SessionRedis\SessionRedisFactory getFactory()
 * @method \Spryker\Yves\SessionRedis\SessionRedisConfig getConfig()
 */
class SessionHandlerConfigurableRedisLockingProviderPlugin extends AbstractPlugin implements SessionHandlerProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Gets a string identifier for configurable Redis session handler with locking mechanism.
     *
     * @api
     *
     * @return string
     */
    public function getSessionHandlerName(): string
    {
        return $this->getConfig()->getSessionHandlerConfigurableRedisLockingName();
    }

    /**
     * {@inheritDoc}
     * - Gets an instance of the configurable Redis session handler with locking mechanism.
     *
     * @api
     *
     * @return \SessionHandlerInterface
     */
    public function getSessionHandler(): SessionHandlerInterface
    {
        return $this->getFactory()->createSessionHandlerConfigurableRedisLocking();
    }
}
