<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerPasswordPolicyPluginInterface
{
    /**
     * Specification:
     *  - This plugin allows to execute configured password policy check for customer's password.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer $resultTransfer
     * @param array $config
     *
     * @return \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer
     */
    public function check(
        CustomerTransfer $customerTransfer,
        CustomerPasswordPolicyResultTransfer $resultTransfer,
        array $config
    ): CustomerPasswordPolicyResultTransfer;

    /**
     * Specification:
     *  - Returns name of password policy plugin implementation.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string;
}
