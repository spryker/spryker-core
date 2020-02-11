<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionRedis\Communication\Plugin\Session;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SessionExtension\Dependency\Plugin\SessionLockReleaserPluginInterface;

/**
 * @method \Spryker\Zed\SessionRedis\Communication\SessionRedisCommunicationFactory getFactory()
 * @method \Spryker\Zed\SessionRedis\SessionRedisConfig getConfig()
 */
class YvesSessionRedisLockReleaserPlugin extends AbstractPlugin implements SessionLockReleaserPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sessionId
     *
     * @return bool
     */
    public function release(string $sessionId): bool
    {
        return $this->getFactory()->createYvesSessionLockReleaser()->release($sessionId);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getSessionHandlerName(): string
    {
        return $this->getConfig()->getSessionHandlerRedisLockingName();
    }
}
