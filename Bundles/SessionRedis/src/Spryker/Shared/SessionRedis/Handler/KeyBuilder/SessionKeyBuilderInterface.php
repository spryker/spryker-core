<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler\KeyBuilder;

interface SessionKeyBuilderInterface
{
    /**
     * @param string $sessionId
     *
     * @return string
     */
    public function buildSessionKey(string $sessionId): string;

    /**
     * @param string $sessionId
     *
     * @return string
     */
    public function buildLockKey(string $sessionId): string;
}
