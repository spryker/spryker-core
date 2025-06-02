<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;

/**
 * Use this plugin to enable Multi-Factor Authentication for a user in login process.
 */
interface AuthenticationHandlerPluginInterface
{
    /**
     * Specification:
     * - Checks if the plugin is applicable for the provided entity.
     *
     * @api
     *
     * @param string $entity
     *
     * @return bool
     */
    public function isApplicable(string $entity): bool;

    /**
     * Specification:
     * - Validates whether the multi-factor authentication method is enabled for the provided user.
     * - Validates whether the code was verified.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validateUserMultiFactorStatus(
        MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
    ): MultiFactorAuthValidationResponseTransfer;
}
