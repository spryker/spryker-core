<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler\KeyBuilder;

class SessionKeyBuilder implements SessionKeyBuilderInterface
{
    protected const SESSION_KEY_PREFIX = 'session';
    protected const SESSION_LOCK_KEY_SUFFIX = 'lock';

    /**
     * @param string $sessionId
     *
     * @return string
     */
    public function buildSessionKey(string $sessionId): string
    {
        return sprintf('%s:%s', static::SESSION_KEY_PREFIX, $sessionId);
    }

    /**
     * @param string $sessionId
     *
     * @return string
     */
    public function buildLockKey(string $sessionId): string
    {
        return sprintf('%s:%s', $this->buildSessionKey($sessionId), static::SESSION_LOCK_KEY_SUFFIX);
    }
}
