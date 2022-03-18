<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SessionUserTransfer;

/**
 * Use this plugin interface to provide functionality to save customer session.
 */
interface SessionUserSaverPluginInterface
{
    /**
     * Specification:
     * - Saves `SessionUser` to persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SessionUserTransfer $sessionUserTransfer
     *
     * @return void
     */
    public function saveSessionUser(SessionUserTransfer $sessionUserTransfer): void;
}
