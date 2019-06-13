<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Business\Lock\SessionLockReleaser;

use Spryker\Zed\Session\Business\Exception\NotALockingSessionHandlerException;
use Spryker\Zed\Session\Business\Lock\SessionLockReleaserInterface;

class SessionLockReleaserPool implements SessionLockReleaserPoolInterface
{
    /**
     * @var \Spryker\Zed\Session\Business\Lock\SessionLockReleaserInterface[]|\Spryker\Zed\SessionExtension\Dependency\Plugin\SessionLockReleaserPluginInterface[]
     */
    protected $lockReleaser;

    /**
     * @param \Spryker\Zed\SessionExtension\Dependency\Plugin\SessionLockReleaserPluginInterface[] $sessionLockReleaserPlugins
     */
    public function __construct(array $sessionLockReleaserPlugins = [])
    {
        $this->addSessionLockReleaserPlugins($sessionLockReleaserPlugins);
    }

    /**
     * @deprecated Use `Spryker\Zed\SessionExtension\Dependency\Plugin\SessionLockReleaserPluginInterface` implementation instead.
     *
     * @param \Spryker\Zed\Session\Business\Lock\SessionLockReleaserInterface $lockReleaser
     * @param string $handlerName
     *
     * @return $this
     */
    public function addLockReleaser(SessionLockReleaserInterface $lockReleaser, $handlerName)
    {
        if (!isset($this->lockReleaser[$handlerName])) {
            $this->lockReleaser[$handlerName] = $lockReleaser;
        }

        return $this;
    }

    /**
     * @param string $handlerName
     *
     * @throws \Spryker\Zed\Session\Business\Exception\NotALockingSessionHandlerException
     *
     * @return \Spryker\Zed\Session\Business\Lock\SessionLockReleaserInterface|\Spryker\Zed\SessionExtension\Dependency\Plugin\SessionLockReleaserPluginInterface
     */
    public function getLockReleaser($handlerName)
    {
        if (isset($this->lockReleaser[$handlerName])) {
            return $this->lockReleaser[$handlerName];
        }

        throw new NotALockingSessionHandlerException(sprintf(
            'The configured session handler "%s" doesn\'t seem to support locking',
            $handlerName
        ));
    }

    /**
     * @param \Spryker\Zed\SessionExtension\Dependency\Plugin\SessionLockReleaserPluginInterface[] $sessionLockReleaserPlugins
     *
     * @return void
     */
    protected function addSessionLockReleaserPlugins(array $sessionLockReleaserPlugins): void
    {
        foreach ($sessionLockReleaserPlugins as $sessionLockReleaserPlugin) {
            $this->lockReleaser[$sessionLockReleaserPlugin->getSessionHandlerName()] = $sessionLockReleaserPlugin;
        }
    }
}
