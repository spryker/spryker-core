<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionExtension\Dependency\Plugin;

use SessionHandlerInterface;

interface SessionHandlerPluginInterface extends SessionHandlerInterface
{
    /**
     * Specification:
     * - Gets a session handler string identifier.
     *
     * @api
     *
     * @return string
     */
    public function getSessionHandlerName(): string;

    /**
     * Specification:
     * - Closes a session.
     *
     * @link https://php.net/manual/en/sessionhandlerinterface.close.php
     *
     * @api
     *
     * @return bool
     */
    public function close(): bool;

    /**
     * Specification:
     * - Destroys a session.
     *
     * @link https://php.net/manual/en/sessionhandlerinterface.destroy.php
     *
     * @api
     *
     * @param string $sessionId
     *
     * @return bool
     */
    public function destroy($sessionId): bool;

    /**
     * Specification:
     * - Cleanup old sessions.
     *
     * @link https://php.net/manual/en/sessionhandlerinterface.gc.php
     *
     * @api
     *
     * @param int $maxLifetime
     *
     * @return bool
     */
    public function gc($maxLifetime): bool;

    /**
     * Specification:
     * - Initializes session.
     *
     * @link https://php.net/manual/en/sessionhandlerinterface.open.php
     *
     * @api
     *
     * @param string $savePath
     * @param string $name
     *
     * @return bool
     */
    public function open($savePath, $name): bool;

    /**
     * Specification:
     * - Reads session data.
     *
     * @link https://php.net/manual/en/sessionhandlerinterface.read.php
     *
     * @api
     *
     * @param string $sessionId
     *
     * @return string
     */
    public function read($sessionId): string;

    /**
     * Specification:
     * - Writes session data.
     *
     * @link https://php.net/manual/en/sessionhandlerinterface.write.php
     *
     * @api
     *
     * @param string $sessionId
     * @param string $sessionData
     *
     * @return bool
     */
    public function write($sessionId, $sessionData): bool;
}
