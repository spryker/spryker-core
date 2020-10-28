<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerResponseTransfer;

interface CustomerPasswordPolicyPluginInterface
{
    /**
     * Specification:
     *  - This plugin allows to execute configured password policy check for customer's password.
     *
     * @api
     *
     * @param string $customerPassword
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $resultTransfer
     * @param mixed[] $config
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validate(
        string $customerPassword,
        CustomerResponseTransfer $resultTransfer,
        array $config
    ): CustomerResponseTransfer;

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
