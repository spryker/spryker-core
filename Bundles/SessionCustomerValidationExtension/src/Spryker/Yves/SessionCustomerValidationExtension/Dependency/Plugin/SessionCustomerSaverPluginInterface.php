<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SessionCustomerTransfer;

/**
 * @deprecated Use {@link \SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionSaverPluginInterface} instead.
 *
 * Use this plugin interface to provide functionality to save customer session.
 */
interface SessionCustomerSaverPluginInterface
{
    /**
     * Specification:
     * - Saves `SessionCustomer` to session storage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SessionCustomerTransfer $sessionCustomerTransfer
     *
     * @return void
     */
    public function saveSessionCustomer(SessionCustomerTransfer $sessionCustomerTransfer): void;
}
