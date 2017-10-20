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
     * @var array
     */
    protected $lockReleaser;

    /**
     * @param \Spryker\Zed\Session\Business\Lock\SessionLockReleaserInterface $lockReleaser
     * @param string $handlerName
     *
     * @return $this
     */
    public function addLockReleaser(SessionLockReleaserInterface $lockReleaser, $handlerName)
    {
        $this->lockReleaser[$handlerName] = $lockReleaser;

        return $this;
    }

    /**
     * @param string $handlerName
     *
     * @throws \Spryker\Zed\Session\Business\Exception\NotALockingSessionHandlerException
     *
     * @return \Spryker\Zed\Session\Business\Lock\SessionLockReleaserInterface
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
}
