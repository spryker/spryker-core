<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler;

interface SessionHandlerInterface
{
    /**
     * @return bool
     */
    public function close(): bool;

    /**
     * @param string $sessionId
     *
     * @return bool
     */
    public function destroy(string $sessionId): bool;

    /**
     * @param int $maxLifetime
     *
     * @return bool
     */
    public function gc(int $maxLifetime): bool;

    /**
     * @param string $savePath
     * @param string $name
     *
     * @return bool
     */
    public function open(string $savePath, string $name): bool;

    /**
     * @param string $sessionId The session id to read data for.
     *
     * @return string
     */
    public function read(string $sessionId): string;

    /**
     * @param string $sessionId
     * @param string $sessionData
     *
     * @return bool
     */
    public function write(string $sessionId, string $sessionData): bool;
}
