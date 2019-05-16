<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionExtension\Dependency\Plugin;

interface SessionLockReleaserPluginInterface
{
    /**
     * Specification:
     *  - Gets the session handler string identifier.
     *
     * @api
     *
     * @return string
     */
    public function getSessionHandlerName(): string;

    /**
     * Specification:
     * - Releases the lock for the session under provided id.
     *
     * @api
     *
     * @param string $sessionId
     *
     * @return bool
     */
    public function release(string $sessionId): bool;
}
