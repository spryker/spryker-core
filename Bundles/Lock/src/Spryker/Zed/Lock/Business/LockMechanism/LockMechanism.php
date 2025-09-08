<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Lock\Business\LockMechanism;

use Generated\Shared\Transfer\LockTransfer;
use Spryker\Zed\Lock\Business\LockFactory\LockFactoryInterface;
use Spryker\Zed\Lock\LockConfig;

class LockMechanism implements LockMechanismInterface
{
    /**
     * @var string
     */
    protected const LOCK_KEY_PREFIX = 'lock-';

    /**
     * @var array<\Symfony\Component\Lock\LockInterface>
     */
    protected array $locks;

    /**
     * @param \Spryker\Zed\Lock\Business\LockFactory\LockFactoryInterface $lockFactory
     */
    public function __construct(protected LockFactoryInterface $lockFactory)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\LockTransfer $lockTransfer
     *
     * @return \Generated\Shared\Transfer\LockTransfer
     */
    public function acquireLock(LockTransfer $lockTransfer): LockTransfer
    {
        $key = $this->generateLockKey($lockTransfer);
        $lock = $this->lockFactory->createLock($key, $lockTransfer->getExpiration() ?: LockConfig::DEFAULT_LOCK_TIMEOUT);
        $this->locks[$key] = $lock;

        return $lockTransfer->setResult($lock->acquire($lockTransfer->getBlocking() ?? false));
    }

    /**
     * @param \Generated\Shared\Transfer\LockTransfer $lockTransfer
     *
     * @return \Generated\Shared\Transfer\LockTransfer
     */
    public function releaseLock(LockTransfer $lockTransfer): LockTransfer
    {
        $key = $this->generateLockKey($lockTransfer);
        if (!isset($this->locks[$key])) {
            $lockTransfer->setResult(false);
        }
        $this->locks[$key]->release();

        return $lockTransfer->setResult(true);
    }

    /**
     * @param \Generated\Shared\Transfer\LockTransfer $lockTransfer
     *
     * @return string
     */
    protected function generateLockKey(LockTransfer $lockTransfer): string
    {
        $lockTransfer->requireKey()
            ->requireEntityName();

        return sprintf('%s-%s:%s', static::LOCK_KEY_PREFIX, $lockTransfer->getEntityName(), $lockTransfer->getKey());
    }
}
