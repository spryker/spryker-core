<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;

/**
 * Use this plugin interface to implement a multi-factor authentication method.
 */
interface MultiFactorAuthPluginInterface
{
    /**
     * Specification:
     * - Returns the name of the multi-factor authentication method.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Specification:
     * - Returns true if the multi-factor authentication method is applicable.
     *
     * @api
     *
     * @param string $multiFactorAuthMethod
     *
     * @return bool
     */
    public function isApplicable(string $multiFactorAuthMethod): bool;

    /**
     * Specification:
     * - Returns the configuration of the multi-factor authentication method.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return string
     */
    public function getConfiguration(MultiFactorAuthTransfer $multiFactorAuthTransfer): string;

    /**
     * Specification:
     * - Sends the multi-factor authentication code to the customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return void
     */
    public function sendCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): void;
}
