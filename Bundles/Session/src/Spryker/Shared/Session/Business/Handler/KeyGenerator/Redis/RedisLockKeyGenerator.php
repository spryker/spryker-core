<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session\Business\Handler\KeyGenerator\Redis;

use Spryker\Shared\Session\Business\Handler\KeyGenerator\LockKeyGeneratorInterface;
use Spryker\Shared\Session\Business\Handler\KeyGenerator\SessionKeyGeneratorInterface;

class RedisLockKeyGenerator implements LockKeyGeneratorInterface
{
    public const KEY_SUFFIX = ':lock';

    /**
     * @var \Spryker\Shared\Session\Business\Handler\KeyGenerator\SessionKeyGeneratorInterface
     */
    protected $sessionKeyGenerator;

    /**
     * @param \Spryker\Shared\Session\Business\Handler\KeyGenerator\SessionKeyGeneratorInterface $sessionKeyGenerator
     */
    public function __construct(SessionKeyGeneratorInterface $sessionKeyGenerator)
    {
        $this->sessionKeyGenerator = $sessionKeyGenerator;
    }

    /**
     * @param string $sessionId
     *
     * @return string
     */
    public function generateLockKey($sessionId)
    {
        return $this->sessionKeyGenerator->generateSessionKey($sessionId) . static::KEY_SUFFIX;
    }
}
