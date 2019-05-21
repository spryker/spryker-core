<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\BusinessOnBehalfExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;

interface CompanyUserChangeAllowedCheckPluginInterface
{
    /**
     * Specification:
     * - Checks if customer is applicable for changing company user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function check(CustomerTransfer $customerTransfer): bool;
}
