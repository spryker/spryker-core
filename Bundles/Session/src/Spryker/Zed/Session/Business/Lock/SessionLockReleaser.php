<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Business\Lock;

use Spryker\Shared\Session\Business\Handler\Lock\SessionLockerInterface;

/**
 * @deprecated Use `Spryker\Zed\SessionExtension\Dependency\Plugin\SessionLockReleaserPluginInterface` instead.
 */
class SessionLockReleaser implements SessionLockReleaserInterface
{
    /**
     * @var \Spryker\Shared\Session\Business\Handler\Lock\SessionLockerInterface
     */
    protected $locker;

    /**
     * @var \Spryker\Zed\Session\Business\Lock\SessionLockReaderInterface
     */
    protected $lockReader;

    /**
     * @param \Spryker\Shared\Session\Business\Handler\Lock\SessionLockerInterface $locker
     * @param \Spryker\Zed\Session\Business\Lock\SessionLockReaderInterface $lockReader
     */
    public function __construct(SessionLockerInterface $locker, SessionLockReaderInterface $lockReader)
    {
        $this->locker = $locker;
        $this->lockReader = $lockReader;
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     */
    public function release($sessionId)
    {
        $lockToken = $this->getLockToken($sessionId);

        if (!$lockToken) {
            return false;
        }

        return $this->locker->unlock($sessionId, $lockToken);
    }

    /**
     * @param string $sessionId
     *
     * @return string
     */
    protected function getLockToken($sessionId)
    {
        return $this->lockReader->getTokenForSession($sessionId);
    }
}
