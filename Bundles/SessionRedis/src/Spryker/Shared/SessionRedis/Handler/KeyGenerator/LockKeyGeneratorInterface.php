<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler\KeyGenerator;

interface LockKeyGeneratorInterface
{
    /**
     * @param string $sessionId
     *
     * @return string
     */
    public function generateLockKey(string $sessionId): string;
}
