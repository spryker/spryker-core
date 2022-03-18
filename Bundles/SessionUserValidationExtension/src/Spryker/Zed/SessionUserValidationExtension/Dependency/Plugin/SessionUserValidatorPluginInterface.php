<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SessionUserTransfer;

/**
 * Use this plugin interface to provide functionality to validate customer session.
 */
interface SessionUserValidatorPluginInterface
{
    /**
     * Specification:
     * - Returns `true` if session is valid for user, `false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SessionUserTransfer $sessionUserTransfer
     *
     * @return bool
     */
    public function isSessionUserValid(SessionUserTransfer $sessionUserTransfer): bool;
}
