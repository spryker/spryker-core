<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface PostLoginMultiFactorAuthenticationPluginInterface
{
    /**
     * Specification:
     * - Checks if the plugin is applicable for the given authentication type.
     *
     * @api
     *
     * @param string $authenticationType
     *
     * @return bool
     */
    public function isApplicable(string $authenticationType): bool;

    /**
     * Specification:
     * - Creates an authentication token.
     *
     * @api
     *
     * @param string $email
     *
     * @return void
     */
    public function createToken(string $email): void;

    /**
     * Specification:
     * - This method is called after the user has been successfully logged in.
     * - It can be used to perform any additional actions that need to be taken after the user has been logged in.
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $abstractTransfer
     *
     * @return void
     */
    public function executeOnAuthenticationSuccess(AbstractTransfer $abstractTransfer): void;
}
