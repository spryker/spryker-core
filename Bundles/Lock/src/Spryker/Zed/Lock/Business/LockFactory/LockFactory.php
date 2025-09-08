<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Lock\Business\LockFactory;

use Symfony\Component\Lock\Key;
use Symfony\Component\Lock\Lock;
use Symfony\Component\Lock\LockInterface;
use Symfony\Component\Lock\PersistingStoreInterface;

class LockFactory implements LockFactoryInterface
{
    /**
     * @param \Symfony\Component\Lock\PersistingStoreInterface $defaultStorage
     */
    public function __construct(
        protected PersistingStoreInterface $defaultStorage
    ) {
    }

    /**
     * @param string $resource
     * @param float|null $ttl
     * @param bool $autoRelease
     *
     * @return \Symfony\Component\Lock\LockInterface
     */
    public function createLock(string $resource, ?float $ttl = 300.0, bool $autoRelease = true): LockInterface
    {
        return $this->createLockFromKey(new Key($resource), $ttl, $autoRelease);
    }

    /**
     * @param \Symfony\Component\Lock\Key $key
     * @param float|null $ttl
     * @param bool $autoRelease
     *
     * @return \Symfony\Component\Lock\LockInterface
     */
    protected function createLockFromKey(Key $key, ?float $ttl = 300.0, bool $autoRelease = true): LockInterface
    {
        return new Lock($key, $this->getPersistingStorageByStrategy(), $ttl, $autoRelease);
    }

    /**
     * @return \Symfony\Component\Lock\PersistingStoreInterface
     */
    protected function getPersistingStorageByStrategy(): PersistingStoreInterface
    {
        return $this->defaultStorage;
    }
}
