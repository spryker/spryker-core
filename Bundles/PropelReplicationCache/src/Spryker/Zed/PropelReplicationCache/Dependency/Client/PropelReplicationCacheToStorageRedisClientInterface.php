<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelReplicationCache\Dependency\Client;

interface PropelReplicationCacheToStorageRedisClientInterface
{
    /**
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key): ?string;

    /**
     * @param string $key
     * @param string $value
     * @param int|null $expireTTL
     *
     * @return bool
     */
    public function set(string $key, string $value, ?int $expireTTL = null): bool;
}
