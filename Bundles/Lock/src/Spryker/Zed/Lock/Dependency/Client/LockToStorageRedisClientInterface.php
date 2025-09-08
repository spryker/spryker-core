<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Lock\Dependency\Client;

interface LockToStorageRedisClientInterface
{
    /**
     * @param string $script
     * @param int $numKeys
     * @param mixed $keysOrArgs
     *
     * @return bool
     */
    public function evaluate(string $script, int $numKeys, ...$keysOrArgs): bool;
}
