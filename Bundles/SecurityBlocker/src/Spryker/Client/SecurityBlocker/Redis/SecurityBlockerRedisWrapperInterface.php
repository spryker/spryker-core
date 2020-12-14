<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker\Redis;

interface SecurityBlockerRedisWrapperInterface
{
    /**
     * @param string $key
     *
     * @return bool
     */
    public function incr(string $key): bool;

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key): ?string;

    /**
     * @param string $key
     * @param string $value
     * @param string|null $expireResolution
     * @param int|null $expireTTL
     * @param string|null $flag
     *
     * @return bool
     */
    public function set(string $key, string $value, ?string $expireResolution = null, ?int $expireTTL = null, ?string $flag = null): bool;
}
