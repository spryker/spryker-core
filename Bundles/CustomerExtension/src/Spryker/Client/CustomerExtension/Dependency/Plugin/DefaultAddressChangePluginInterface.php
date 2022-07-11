<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;

/**
 * Provides extension capabilities for actions executed after a customer address has been created or updated.
 */
interface DefaultAddressChangePluginInterface
{
    /**
     * Specification:
     *  - Executes additional operations after a customer address has been created or updated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function process(CustomerTransfer $customerTransfer);
}
