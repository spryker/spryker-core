<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SessionCustomerTransfer;

/**
 * Use this plugin interface to provide functionality to validate customer session.
 */
interface SessionCustomerValidatorPluginInterface
{
    /**
     * Specification:
     * - Returns `true` if session is valid for customer, `false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SessionCustomerTransfer $sessionCustomerTransfer
     *
     * @return bool
     */
    public function isSessionCustomerValid(SessionCustomerTransfer $sessionCustomerTransfer): bool;
}
