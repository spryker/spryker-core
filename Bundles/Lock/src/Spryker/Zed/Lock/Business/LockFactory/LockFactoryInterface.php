<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Lock\Business\LockFactory;

use Symfony\Component\Lock\LockInterface;

interface LockFactoryInterface
{
    /**
     * Creates a lock for the given resource.
     *
     * @param string $resource
     * @param float|null $ttl
     * @param bool $autoRelease
     *
     * @return \Symfony\Component\Lock\LockInterface
     */
    public function createLock(string $resource, ?float $ttl = 300.0, bool $autoRelease = true): LockInterface;
}
