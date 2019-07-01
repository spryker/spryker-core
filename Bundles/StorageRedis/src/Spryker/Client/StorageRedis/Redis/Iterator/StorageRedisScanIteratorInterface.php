<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageRedis\Redis\Iterator;

interface StorageRedisScanIteratorInterface
{
    /**
     * @param string $pattern
     * @param int $limit
     * @param int $cursor
     *
     * @return array [string, string[]]
     */
    public function scanKeys(string $pattern, int $limit, int $cursor): array;
}
