<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler\KeyGenerator\Redis;

use Spryker\Shared\SessionRedis\Handler\KeyGenerator\SessionKeyGeneratorInterface;

class RedisSessionKeyGenerator implements SessionKeyGeneratorInterface
{
    public const KEY_PREFIX = 'session:';

    /**
     * @param string $sessionId
     *
     * @return string
     */
    public function generateSessionKey(string $sessionId): string
    {
        return static::KEY_PREFIX . $sessionId;
    }
}
