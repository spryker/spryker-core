<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler\Lock;

interface SessionLockerInterface
{
    /**
     * @param string $sessionId
     *
     * @return bool
     */
    public function lock($sessionId): bool;

    /**
     * @return void
     */
    public function unlockCurrent(): void;

    /**
     * @param string $sessionId
     * @param string $token
     *
     * @return bool
     */
    public function unlock($sessionId, $token): bool;
}
