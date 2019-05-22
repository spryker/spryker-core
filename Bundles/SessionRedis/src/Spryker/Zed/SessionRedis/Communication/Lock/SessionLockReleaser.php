<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionRedis\Communication\Lock;

use Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface;

class SessionLockReleaser implements SessionLockReleaserInterface
{
    /**
     * @var \Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface
     */
    protected $locker;

    /**
     * @var \Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReaderInterface
     */
    protected $lockReader;

    /**
     * @param \Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface $locker
     * @param \Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReaderInterface $lockReader
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
    public function release($sessionId): bool
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
    protected function getLockToken($sessionId): string
    {
        return $this->lockReader->getTokenForSession($sessionId);
    }
}
