<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionExtension\Dependency\Plugin;

use SessionHandlerInterface;

interface SessionHandlerPluginInterface
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
     *  - Gets the session handler implementation.
     *
     * @api
     *
     * @return \SessionHandlerInterface
     */
    public function getSessionHandler(): SessionHandlerInterface;
}
