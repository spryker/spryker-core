<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session\Business\Handler\Lock;

/**
 * @deprecated Use `Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface` instead.
 */
interface SessionLockerInterface
{
    /**
     * @param string $sessionId
     *
     * @return bool
     */
    public function lock($sessionId);

    /**
     * @return void
     */
    public function unlockCurrent();

    /**
     * @param string $sessionId
     * @param string $token
     *
     * @return bool
     */
    public function unlock($sessionId, $token);
}
